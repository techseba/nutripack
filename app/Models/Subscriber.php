<?php

namespace App\Models;

use App\Models\Plan;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;

class Subscriber extends Model
{
    // use SoftDeletes;

    protected $table = 'subscribers';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'plan_id',
        'user_id',
        'days_of_week_selected',
        'subscription_days',
        'starting_date',
        'expires_date',
        'delivery_time',
        'phone',
        'house',
        'road',
        'block',
        'area',
        'additional_direction',
        'allergens',
        'promo_code_id',
        'promo_code',
        'subtotal',
        'discount_amount',
        'total',
        'payment_status',
        'status',
        'updater_id',
        'payment_method',
        'transaction_id',
        'created_by_ip',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'days_of_week_selected' => 'integer',
        'starting_date' => 'date',
        'expires_date' => 'date',
        'delivery_time' => 'string',
        'allergens' => 'array',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'promo_code_id' => 'integer',
    ];

    /**
     * Default attribute values
     */
    protected $attributes = [
        'subtotal' => 0,
        'discount_amount' => 0,
        'total' => 0,
        'payment_status' => 'unpaid',
        'status' => 'active',
    ];

    // Optional: সবসময় normalized output চান
    public function getAllergensAttribute($value)
    {
        $arr = $this->castAttribute('allergens', $value) ?? [];
        return collect($arr)->map(fn($i) => trim($i))->filter()->values()->toArray();
    }


    /**
     * Booted: set created_by_ip automatically
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->created_by_ip)) {
                $model->created_by_ip = Request::ip();
            }
        });
    }

    /**
     * Relations
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }

    public function deliveryDays()
    {
        return $this->hasMany(SubscriberDeliveryDay::class);
    }

    public function exclusions()
    {
        return $this->hasMany(SubscriberExclusion::class);
    }

    public function mealSelections()
    {
        return $this->hasMany(\App\Models\SubscriberMealSelection::class);
    }


    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Helper: calculate discount for a given PromoCode and subtotal
     * (does not persist anything)
     *
     * @param  \App\Models\PromoCode  $promo
     * @param  float  $subtotal
     * @return float  discount amount
     */
    public static function calculateDiscountForPromo(PromoCode $promo, float $subtotal): float
    {
        return $promo->calculateDiscount($subtotal);
    }

    /**
     * Apply promo and save subscriber inside a DB transaction.
     *
     * This method:
     *  - locks the promo row (lockForUpdate) to avoid race conditions
     *  - re-validates expiry and usage limits via PromoCode model helpers
     *  - calculates discount and updates subscriber monetary fields
     *  - increments promo usage safely
     *
     * @param  string|null  $promoCodeString
     * @param  float  $subtotal
     * @param  \App\Models\User|null  $user
     * @return array ['success' => bool, 'message' => string]
     */
    public function applyPromoAndSave(?string $promoCodeString, float $subtotal, ?User $user = null): array
    {
        // ensure subtotal is set
        $this->subtotal = round((float) $subtotal, 2);

        if (empty($promoCodeString)) {
            // no promo: just calculate total and save
            $this->discount_amount = 0;
            $this->total = $this->subtotal;
            $this->save();
            return ['success' => true, 'message' => 'Saved without promo.'];
        }

        return DB::transaction(function () use ($promoCodeString, $user) {
            // lock promo row for update to prevent concurrent overuse
            $promo = PromoCode::where('promo_code', $promoCodeString)->lockForUpdate()->first();

            if (!$promo) {
                // save without promo
                $this->promo_code_id = null;
                $this->promo_code = null;
                $this->discount_amount = 0;
                $this->total = $this->subtotal;
                $this->save();
                return ['success' => false, 'message' => 'Invalid promo code.'];
            }

            // re-check expiry and usage rules using model helpers
            if ($promo->isExpired()) {
                $this->promo_code_id = null;
                $this->promo_code = null;
                $this->discount_amount = 0;
                $this->total = $this->subtotal;
                $this->save();
                return ['success' => false, 'message' => 'Promo code has expired.'];
            }

            if (method_exists($promo, 'canBeUsedBy') && !$promo->canBeUsedBy($user)) {
                $this->promo_code_id = null;
                $this->promo_code = null;
                $this->discount_amount = 0;
                $this->total = $this->subtotal;
                $this->save();
                return ['success' => false, 'message' => 'Promo cannot be used by this user.'];
            }

            // calculate discount and final total
            $discount = $promo->calculateDiscount($this->subtotal);
            $finalTotal = max(0, $this->subtotal - $discount);

            // persist subscriber with promo info
            $this->promo_code_id = $promo->id;
            $this->promo_code = $promo->promo_code;
            $this->discount_amount = round($discount, 2);
            $this->total = round($finalTotal, 2);
            $this->save();

            // increment promo usage safely if model has uses column
            if (Schema::hasColumn($promo->getTable(), 'uses')) {
                $promo->increment('uses');
            }

            return ['success' => true, 'message' => 'Promo applied and subscriber saved.'];
        });
    }

    /**
     * Convenience accessor for subscription length in days (if dates present)
     */
    public function getDurationDaysAttribute(): ?int
    {
        if ($this->starting_date && $this->expires_date) {
            return $this->starting_date->diffInDays($this->expires_date);
        }
        return null;
    }
}
