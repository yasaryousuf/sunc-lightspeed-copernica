<?php


namespace App\Custom\Lightspeed;


use App\Models\LightspeedAuth;

class Product extends LightspeedModel
{


    function __construct()
    {
        parent::__construct();
    }

    function get() {
        return $this->lightspeed->products->get();
    }
    
    function getById($customer_id) {
        return $this->lightspeed->products->get($customer_id);
    }

    function count() {
        return $this->lightspeed->products->count();
    }

}