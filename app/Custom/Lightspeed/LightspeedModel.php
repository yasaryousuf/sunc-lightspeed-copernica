<?php


namespace App\Custom\Lightspeed;


use App\Custom\WebshopappApiClient;
use App\Models\LightspeedAuth;
use Illuminate\Support\Facades\Auth;

class LightspeedModel
{
    private $api_secret = '';
    private $api_key = '';
    private $language = 'nl';
    private $cluster = "eu1";
    public $lightspeed;

    function __construct()
    {
        $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->firstOrfail();
        $this->api_key = $lightspeedAuth->api_key;
        $this->api_secret = $lightspeedAuth->api_secret;
        $this->lightspeed = new WebshopappApiClient($this->cluster, $this->api_key, $this->api_secret, $this->language);
    }

}