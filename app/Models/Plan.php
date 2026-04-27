<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'diet_plan_id',
        'plan_category_id',
        'min_calories',
        'max_calories',
        'protein',
        'carbs',
        'fat',
        'fiber',
        'days_of_week',
        'price',
        'user_id',
        'status',
    ];

    /**
     * সম্পর্ক: Plan belongs to DietPlan
     */
    public function dietPlan()
    {
        return $this->belongsTo(DietPlan::class);
    }

    /**
     * সম্পর্ক: Plan belongs to PlanCategory
     */
    public function planCategory()
    {
        return $this->belongsTo(PlanCategory::class, 'plan_category_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
