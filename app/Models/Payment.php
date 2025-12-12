<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['payment_id', 'order_id', 'status', 'method', 'amount', 'response'];
    protected $casts = ['response' => 'array', 'amount' => 'decimal:2'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
