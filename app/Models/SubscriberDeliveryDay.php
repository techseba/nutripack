<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriberDeliveryDay extends Model
{
    protected $fillable = ['subscriber_id', 'delivery_date', 'day_of_week', 'items', 'status'];
    protected $casts = [
        'delivery_date' => 'date',
        'items' => 'array',
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

}
