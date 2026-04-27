<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealType extends Model
{
    use HasFactory, SoftDeletes;

    // Mass assignment protection
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
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
        return $this->hasMany(Meal::class);
    }

    public function selections()
    {
        return $this->hasMany(SubscriberMealSelection::class);
    }

}
