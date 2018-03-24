<?php

namespace DEWordpressPlugin;

class BaseApiClient {
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    public function __construct() {}
    public function __destruct() {}

    protected function get($url, $obj = array()) {
        return RestCurl::exec(self::GET, $url, $obj);
    }

    protected function post($url, $obj = array()) {
        return $this->exec(self::POST, $url, $obj);
    }

    protected function put($url, $obj = array()) {
        return $this->exec(self::PUT, $url, $obj);
    }

    protected function delete($url, $obj = array()) {
        return $this->exec(self::DELETE, $url, $obj);
    }

    protected function setupAuth($curl) {
        return $curl;
    }

    private function exec($method, $url, $obj = array()) {
        //TODO: I think the curl php extensions are required for wordpress, but I'll need to double check that before calling this good.
        //          --nathan
        $curl = curl_init();

        switch($method) {
            case self::GET:
                if(strrpos($url, "?") === FALSE) {
                    $url .= '?' . http_build_query($obj);
                }
                break;
            case self::POST: 
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($obj));
                break;
            case self::PUT:
            case self::DELETE:
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method)); // method
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($obj)); // body
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json')); 
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);

        $curl = $this->setupAuth($curl);

        // Exec
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        // Data
        $header = trim(substr($response, 0, $info['header_size']));
        $body = substr($response, $info['header_size']);

        return array('status' => $info['http_code'], 'header' => $header, 'data' => json_decode($body));
    }
}
