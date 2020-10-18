<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'productTitle',
        'varientId',
        'varientTitle',
        'quantityOrdered',
        'quantityReturned',
        'basePriceIncl',
        'priceIncl',
        'email',
    ];
}
