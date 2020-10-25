<?php


namespace App\Custom\Copernica;


class CopernicaRestAPI
{
    /**
     *  The access token
     * @var string
     */
    private $token;

    /**
     *  The version of the REST API
     */
    private $version;

    /**
     *  The API host
     */
    private $host = 'https://api.copernica.com';

    /**
     *  Constructor
     * @param string      Access token
     * @param int         Version parameter (optional)
     */
    public function __construct($token, $version = 2)
    {
        // copy the token
        $this->token = $token;

        // set the version
        $this->version = $version;
    }

    /**
     *  Do a GET request
     *
     * @param string      Resource to fetch
     * @param array       Associative array with additional parameters
     * @return array       Associative array with the result
     */
    public function get($resource, array $parameters = array())
    {
        // the query string
        $query = http_build_query(array('access_token' => $this->token) + $parameters);

        // construct curl resource
        $curl = curl_init("{$this->host}/v{$this->version}/$resource?$query");

        // additional options
        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => true));

        // do the call
        $answer = curl_exec($curl);

        // retrieve the HTTP status code
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // bad request
        if (!$httpCode || $httpCode == 400) {
            throw new \Exception(json_decode($answer, true)['error']['message']);
        };


        // do we have a JSON output? we can be nice and parse it for the user
        if (curl_getinfo($curl, CURLINFO_CONTENT_TYPE) == 'application/json') {

            // the JSON parsed output
            $jsonOut = json_decode($answer, true);

            // if we have a json error then we have some garbage in the out
            if (json_last_error() != JSON_ERROR_NONE) throw new Exception('Unexpected input: ' . $answer);

            // return the json
            return $jsonOut;
        }

        // clean up curl resource
        curl_close($curl);

        // it's not JSON so we out it just like that
        return $answer;
    }

    /**
     *  Execute a POST request.
     *
     * @param string          Resource name
     * @param array           Associative array with data to post
     *
     * @return mixed           ID of created entity, or simply true/false
     *                          to indicate success or failure
     */
    public function post($resource, array $data = array(), $curlopt_header = false)
    {
        // Pass the request on
        return $this->sendData($resource, $data, array(), "POST", $curlopt_header);
    }

    /**
     *  Execute a PUT request.
     *
     * @param string          Resource name
     * @param array           Associative array with data to post
     * @param array           Associative array with additional parameters
     *
     * @return mixed           ID of created entity, or simply true/false
     *                          to indicate success or failure
     */
    public function put($resource, $data, array $parameters = array())
    {
        // Pass the request on
        return $this->sendData($resource, $data, $parameters, "PUT");
    }

    /**
     *  Execute a request to create/edit data. (PUT + POST)
     *
     * @param string          Resource name
     * @param array           Associative array with data to post
     * @param array           Associative array with additional parameters
     * @param string          Method to use (POST or PUT)
     *
     * @return mixed           ID of created entity, or simply true/false
     *                          to indicate success or failure
     */
    public function sendData($resource, array $data = array(), array $parameters = array(), $method = "POST", $curlopt_header = false)
    {
        // the query string
        $query = http_build_query(array('access_token' => $this->token) + $parameters);

        // construct curl resource
        $curl = curl_init("{$this->host}/v{$this->version}/$resource?$query");

        // data will be json encoded
        $data = json_encode($data);

        // set the options for a POST method
        if ($method == "POST") {
            $options = array(
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array('content-type: application/json'),
                CURLOPT_POSTFIELDS => $data
            );

            if ($curlopt_header) {
                $options[CURLOPT_HEADER] = true; 
            }
        }
        // set the options for a PUT method
        else { 
            $options = array(
                CURLOPT_CUSTOMREQUEST => 'PUT', 
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array('content-type: application/json', 'content-length: ' . strlen($data)),
                CURLOPT_POSTFIELDS => $data
            );

            if ($curlopt_header) {
                $options[CURLOPT_HEADER] = true; 
            }
        }

        // additional options
        curl_setopt_array($curl, $options);


        $answer = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if (!$httpCode || $httpCode == 400) {
            throw new \Exception(json_decode($answer, true)['error']['message']);
        };

        if (!preg_match('/X-Created:\s?(\d+)/i', $answer, $matches)) return true;
        return $matches[1];
    }

    /**
     *  Execute a DELETE request
     *
     * @param string      Resource name
     * @return bool        Success?
     */
    public function delete($resource)
    {
        // the query string
        $query = http_build_query(array('access_token' => $this->token));

        // construct curl resource
        $curl = curl_init("{$this->host}/v{$this->version}/$resource?$query");

        // additional options
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => 'DELETE'
        ));

        // do the call
        $answer = curl_exec($curl);

        // clean up curl resource
        curl_close($curl);

        // done
        return $answer;
    }
}
