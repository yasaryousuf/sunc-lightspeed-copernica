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

    function count() {
        return $this->lightspeed->orders->count();
    }

}