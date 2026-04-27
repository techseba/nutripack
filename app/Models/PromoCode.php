<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PromoCode extends Model
{
    protected $fillable = ['promo_code','type','value','expires_at','user_id','status'];

    protected $casts = [
        'value' => 'decimal:2',
        'expires_at' => 'datetime', // <-- এটা লাগবে
    ];

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canBeUsedBy($user = null, ?float $subtotal = null): bool
    {
        return $this->canBeUsedByWithReason($user, $subtotal)['ok'];
    }

    /**
     * Detailed check with reason for UI/logging
     *
     * @param  \App\Models\User|null  $user
     * @param  float|null  $subtotal
     * @return array ['ok' => bool, 'reason' => string|null]
     */
    public function canBeUsedByWithReason($user = null, ?float $subtotal = null): array
    {
        // 1. active & date window
        if (property_exists($this, 'active') && $this->active === false) {
            return ['ok' => false, 'reason' => 'inactive'];
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return ['ok' => false, 'reason' => 'not_started'];
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return ['ok' => false, 'reason' => 'expired'];
        }

        // 2. min subtotal (optional)
        if (! is_null($this->min_subtotal) && ! is_null($subtotal) && $subtotal < (float) $this->min_subtotal) {
            return ['ok' => false, 'reason' => 'min_subtotal_not_met'];
        }

        // 3. global max uses
        if (! is_null($this->max_uses) && isset($this->uses) && $this->uses >= $this->max_uses) {
            return ['ok' => false, 'reason' => 'max_uses_reached'];
        }

        // 4. per-user limit
        if ($user && ! is_null($this->per_user_limit)) {
            $userUses = DB::table('subscribers')
                ->where('user_id', $user->id)
                ->where('promo_code_id', $this->id)
                ->count();

            if ($userUses >= $this->per_user_limit) {
                return ['ok' => false, 'reason' => 'per_user_limit_reached'];
            }
        }

        // 5. allowed plans (optional, if you store allowed_plan_ids as array)
        if (! empty($this->allowed_plan_ids) && is_array($this->allowed_plan_ids)) {
            // you need to pass current plan id via $user or check externally
            // skip strict check here to keep simple
        }

        return ['ok' => true, 'reason' => null];
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'fixed') {
            return min((float)$this->value, $amount);
        }

        // percentage
        return round($amount * ((float)$this->value / 100), 2);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
