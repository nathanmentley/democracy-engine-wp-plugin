<?php

namespace DEWordpressPlugin;

class DEApiClient extends \DEWordpressPlugin\BaseApiClient {
    protected $domain;
    protected $username;
    protected $password;
    protected $account_id;
    protected $recipient_id;

    public function __construct($domain, $username, $password, $account_id, $recipient_id) {
        parent::__construct();

        $this->domain = $domain;
        $this->username = $username;
        $this->password = $password;
        $this->account_id = $account_id;
        $this->recipient_id = $recipient_id;
    }

    public function __destruct() {
        parent::__destruct();
    }

    protected function setupAuth($curl) {
        curl_setopt($curl, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);

        return $curl;
    }

    public function createDonation($data) {
        $request = array("donation" => $data);
        $url = 'https://' . $this->domain . '/subscribers/' . $this->account_id . '/donations.json';
        return $this->post($url, $request);
    }
}
