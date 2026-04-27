<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DayWiseMeal extends Model
{
    protected $fillable = [
        'date',
        'meal_type_id',
        'meal_id',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function mealType(): BelongsTo
    {
        return $this->belongsTo(MealType::class);
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

}
