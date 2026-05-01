<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionalMeal extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'unit_price',
        'max_quantity',
        'status',
        'description',
        'user_id',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'unit_price'    => 'decimal:2',
        'max_quantity'  => 'integer',
        'status'        => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    /**
     * Default attribute values
     */
    protected $attributes = [
        'unit_price'   => 0.00,
        'max_quantity' => 1,
        'status'       => 'active',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Mutators
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }

    public function setUnitPriceAttribute($value)
    {
        // ensure non-negative decimal
        $price = is_null($value) ? 0.00 : (float) $value;
        $this->attributes['unit_price'] = max(0, round($price, 2));
    }

    public function setMaxQuantityAttribute($value)
    {
        $qty = is_null($value) ? 1 : (int) $value;
        $this->attributes['max_quantity'] = max(0, $qty);
    }

    /**
     * Accessors
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    /**
     * Helper methods
     */
    public function lineTotal(int $quantity): float
    {
        $qty = max(0, min($quantity, $this->max_quantity ?? 0));
        return round((float) $this->unit_price * $qty, 2);
    }
}
