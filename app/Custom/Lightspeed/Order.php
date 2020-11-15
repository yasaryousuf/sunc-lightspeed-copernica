<?php


namespace App\Custom\Lightspeed;


use App\Models\LightspeedAuth;

class Order extends LightspeedModel
{
  
    function __construct()
    {
        parent::__construct();
    }

    function get() {
        return $this->lightspeed->orders->get();
    }
    public function orderProducts($orderId)
    {
        return $this->lightspeed->ordersProducts->get($orderId);
    }

    public function orderProduct($order_id, $orderProduct_id)
    {
        return $this->lightspeed->ordersProducts->get($order_id, $orderProduct_id);
    }

    public function product($product_id)
    {
        return $this->lightspeed->products->get($product_id);
    }

    public function variant($variant_id)
    {
        return $this->lightspeed->variants->get($variant_id);
    }
    
    function count() {
        return $this->lightspeed->orders->count();
    }

}