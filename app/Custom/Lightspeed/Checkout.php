<?php


namespace App\Custom\Lightspeed;


use App\Models\LightspeedAuth;

class Checkout extends LightspeedModel
{


    function __construct()
    {
        parent::__construct();
    }

    function get() {
        return $this->lightspeed->checkouts->get();
    }

    function count() {
        return $this->lightspeed->checkouts->count();
    }

}