<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'orderId', 'customerId', 'orderNumber', 'createdAt', 'updatedAt', 'status', 'priceIncl', 'email', 'deliveryDate', 'pickupDate'];

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'orderId', 'orderId');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\OrderPerson', 'customerId', 'customerId');
    }
}
