<?php


namespace App\Custom\Lightspeed;


use App\Models\LightspeedAuth;

class Discount extends LightspeedModel
{


    function __construct()
    {
        parent::__construct();
    }

    function get() {
        return $this->lightspeed->discounts->get();
    }

    function count() {
        return $this->lightspeed->discounts->count();
    }

}