<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id',
        'item_image',
        'quantity',
        'price',
        'status',
        'payment_status',
        'admin_remark',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
