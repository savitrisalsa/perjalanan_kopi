<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method',
        'amount_paid',
        'change_amount',
        'payment_status',
        'paid_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}