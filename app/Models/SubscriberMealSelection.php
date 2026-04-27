<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriberMealSelection extends Model
{
    protected $table = 'subscriber_meal_selections';

    protected $fillable = [
        'subscriber_id',
        'date',
        'meal_type_id',
        'meal_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function mealType(): BelongsTo
    {
        return $this->belongsTo(MealType::class);
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

}
