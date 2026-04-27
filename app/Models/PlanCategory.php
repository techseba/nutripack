<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'diet_plan_id',
        'days_of_plan',
        'min_calories',
        'max_calories',
        'protein',
        'carbs',
        'fat',
        'fiber',
        'image',
        'user_id',
        'status',
    ];

    protected $casts = [
        'min_calories' => 'decimal:2',
        'max_calories' => 'decimal:2',
        'protein'      => 'decimal:2',
        'carbs'        => 'decimal:2',
        'fat'          => 'decimal:2',
        'fiber'        => 'decimal:2',
        'days_of_plan' => 'integer',
        'status'       => 'string',
    ];

    // Relations
    public function dietPlan()
    {
        return $this->belongsTo(DietPlan::class);
    }

    public function mealTypes()
    {
        return $this->belongsToMany(MealType::class, 'meal_type_plan_category');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function plans()
    {
        return $this->hasMany(Plan::class);
    }



}
