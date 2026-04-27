<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SubscriberMealSelection;

class Meal extends Model
{
    use HasFactory, SoftDeletes;

    // Mass assignment protection
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'calories',
        'protein',
        'carbs',
        'fat',
        'fiber',
        'price',
        'status',
        'is_guest_meal',
        'meal_type_id',
        'user_id',
    ];

    protected $casts = [
        'is_guest_meal' => 'boolean',
    ];

    // Relations
    protected $with = [
        'mealType',
    ];


    // Meal belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Meal belongs to one MealType
    public function mealType()
    {
        return $this->belongsTo(MealType::class);
    }

    // Meal belongs to many DietPlans (pivot table: diet_plan_meal)
    public function dietPlans()
    {
        return $this->belongsToMany(DietPlan::class)
            ->withTimestamps();
    }

    // Meal belongs to many Ingredients (pivot table: ingredient_meal)
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)
            ->withTimestamps();
    }

    public function selections()
    {
        return $this->hasMany(SubscriberMealSelection::class);
    }

}
