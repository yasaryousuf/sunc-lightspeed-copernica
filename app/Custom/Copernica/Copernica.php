<?php


namespace App\Custom\Copernica;


use App\Models\CopernicaAuth;
use Illuminate\Support\Facades\Auth;

class Copernica
{
    const SUBSCRIBER_DATABASE_NAME = "ltoc_subscriber";
    const DISCOUNT_DATABASE_NAME = "ltoc_discount";
    const ORDER_DATABASE_NAME = "ltoc_order";
    const USER_DATABASE_NAME = "ltoc_user";
    const ORDER_PERSON_COLLECTION_NAME = "ltoc_order_person";
    const ORDER_PRODUCT_COLLECTION_NAME = "ltoc_order_product";
    const DATABASE_DESCRIPTION = "a description of the database";

    private $token;
    public $copernicaApi;

    function __construct()
    {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->firstOrfail();
        $this->token = $copernicaAuth->token;
        $this->copernicaApi = new CopernicaRestAPI($this->token, 2);
    }
    function getAllDatabases () {
        $parameters = [ 'limit' => 100];
        return $this->copernicaApi->get("databases", $parameters);
    }
    function getAllCollections ($databaseID) {
        $parameters = [ 'limit' => 100];
        return $this->copernicaApi->get("database/{$databaseID}/collections", $parameters);
    }
    function getDatabaseId ($databases, $database_name) {
        foreach ($databases as $database) {
            if ($database['name'] == $database_name) {
                return $database['ID'];
            }
        }
        return false;
    }
    function getcollectionId ($collections, $collection_name) {
        foreach ($collections as $collection) {
            if ($collection['name'] == $collection_name) {
                return $collection['ID'];
            }
        }
        return false;
    }
}