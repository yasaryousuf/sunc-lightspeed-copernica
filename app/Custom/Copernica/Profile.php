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


    function create($fields = [], $id, $curlopt_header = false) {
        $data = [ 'fields' => $fields];
        return $this->copernicaApi->post("database/".$id."/profiles", $data, $curlopt_header);
    }

    function update($fields = [], $id, $param) {
        $data = [ 'fields' => $fields];
        return $this->copernicaApi->put("database/".$id."/profiles", $data, $param);
    }

    function createSubprofile($profileID, $collectionID, $data, $curlopt_header = false) {
        return $this->copernicaApi->post("profile/{$profileID}/subprofiles/{$collectionID}", $data, $curlopt_header);
    }

}