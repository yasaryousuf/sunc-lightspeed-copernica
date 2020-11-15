<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'orderId',
        'orderRowId',
        'productId',
        'productTitle',
        'varientId',
        'varientTitle',
        'quantityOrdered',
        'quantityReturned',
        'basePriceIncl',
        'priceIncl',
        'email',
        'profile_id'
    ];
    
    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'orderId', 'orderId');
    }
}
