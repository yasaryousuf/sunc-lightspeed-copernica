<?php


namespace App\Custom\Copernica;


class Database extends Copernica
{

    function __construct()
    {
        parent::__construct();
    }

    function getAll () {
        $parameters = [ 'limit' => 100];
        return $this->copernicaApi->get("databases", $parameters);
    }

    function createDatabase ($name) {
        $data = array(
            'name'          =>  $name,
            'description'   =>  static::DATABASE_DESCRIPTION
        );

        return $this->copernicaApi->post("databases", $data);
    }
    function createCollection ($database_id, $name) {
        $collectionData = array(
            'name'      =>  $name,
        );

        return $this->copernicaApi->post("database/{$database_id}/collections", $collectionData);

    }

    function create() {

        $data = array(
            'name'          =>  static::DISCOUNT_DATABASE_NAME,
            'description'   =>  static::DATABASE_DESCRIPTION
        );

        try {
            $this->copernicaApi->post("databases", $data);
        } catch (\Throwable $th) {
            //throw $th;
        }

        $data = array(
            'name'          =>  static::ORDER_DATABASE_NAME,
            'description'   =>  static::DATABASE_DESCRIPTION
        );

        try {
            $this->copernicaApi->post("databases", $data);
        } catch (\Throwable $th) {
            //throw $th;
        }


        try {
            $databases = $this->getAll();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $databaseID = $this->getDatabaseId($databases['data'], static::ORDER_DATABASE_NAME);

        $collectionData = array(
            'name'      =>  static::ORDER_PERSON_COLLECTION_NAME,
        );

        try {
            $this->copernicaApi->post("database/{$databaseID}/collections", $data);
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        $collectionData = array(
            'name'      =>  static::ORDER_PRODUCT_COLLECTION_NAME,
        );

        try {
            $this->copernicaApi->post("database/{$databaseID}/collections", $data);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return;
    }

    function createField($data, $database_name) {
        try {
            $databases = $this->getAll();
        } catch (\Exception $e) {

        }
        $id = $this->getDatabaseId($databases['data'], $database_name);
        return $this->copernicaApi->post("database/". $id ."/fields", $data);
    }

    function createCollectionField($data, $collectionID) {
        return $this->copernicaApi->post("collection/{$collectionID}/fields", $data);
    }
}