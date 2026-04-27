<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DietPlan extends Model
{
    use HasFactory, SoftDeletes;

    // Mass assignment protection
    protected $fillable = [
        'name',
        'slug',
        'description',
        'diet_plan_type',
        'image',
        'color',
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
        return $this->belongsToMany(Meal::class)->withTimestamps();
    }

    public function planCategories()
    {
        return $this->belongsToMany(PlanCategory::class)->withTimestamps();
    }
}
