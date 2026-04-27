<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use HasFactory, SoftDeletes;

    // Mass assignment protection
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'allergen_indicator',
        'status',
        'user_id',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class)
            ->withTimestamps();
    }

    // Accessor for human-readable allergen_indicator
    public function getAllergenIndicatorLabelAttribute()
    {
        return match ($this->allergen_indicator) {
            'contains_allergen' => 'Contains Allergen',
            'no_allergen' => 'No Allergen',
            'unknown' => 'Unknown',
            default => ucfirst($this->allergen_indicator),
        };
    }

}
