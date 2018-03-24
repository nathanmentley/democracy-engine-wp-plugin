<?php

namespace DEWordpressPlugin;

class DEApiClient extends \DEWordpressPlugin\BaseApiClient {
    protected $domain;
    protected $username;
    protected $password;
    protected $account_id;

    public function __construct($domain, $username, $password, $account_id) {
        parent::__construct();

        $this->domain = $domain;
        $this->username = $username;
        $this->password = $password;
        $this->account_id = $account_id;
    }

    public function __destruct() {
        parent::__destruct();
    }
    
    protected function setupAuth($curl) {
        $curl = $parent::setupAuth($curl);


        return $curl;
    }
}
