<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'item_image',
        'quantity',
        'length_inch',
        'length_cm',
        'breadth_inch',
        'breadth_cm',
        'description',
        'price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
