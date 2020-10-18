<?php


namespace App\Custom\Lightspeed;


use App\Models\LightspeedAuth;

class Customer extends LightspeedModel
{


    function __construct()
    {
        parent::__construct();
    }

    function get() {
        return $this->lightspeed->customers->get();
    }

    
    function getById($customer_id) {
        return $this->lightspeed->customers->get($customer_id);
    }

    function count() {
        return $this->lightspeed->customers->count();
    }

}