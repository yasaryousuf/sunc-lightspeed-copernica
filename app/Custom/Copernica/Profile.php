<?php


namespace App\Custom\Copernica;


class Profile extends Copernica
{
    function __construct()
    {
        parent::__construct();
    }

    function getAll () {
        $parameters = [ 'limit' => 100];
        return $this->copernicaApi->get("profiles", $parameters);
    }


    function create($fields = [], $id) {
        $data = [ 'fields' => $fields];
        return $this->copernicaApi->post("database/".$id."/profiles", $data);
    }

    function createSubprofile($profileID, $collectionID, $data) {
        return $this->copernicaApi->post("profile/{$profileID}/subprofiles/{$collectionID}", $data);
    }

}