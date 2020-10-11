<?php


namespace App\Custom\Lightspeed;


use App\Models\LightspeedAuth;

class Subscriber extends LightspeedModel
{


    function __construct()
    {
        parent::__construct();
    }

    function get() {
        return $this->lightspeed->subscriptions->get();
    }

    function count() {
        return $this->lightspeed->subscriptions->count();
    }

}