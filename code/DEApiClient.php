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

    public function createDonation() {
        return $this->post(
            'https://' . $this->domain . '/subscribers/' . $this->account_id . '/donations.json',
            array(
                "donation" => array(
                    "line_items" => array(
                        array(
                            "amount" => "1.69",
                            "recipient_id" => $this->recipient_id
                        )
                    ),
                    "donor_last_name" => "Smith",
                    "donor_first_name" => "John",
                    "donor_zip" => "20001",
                    "donor_address1" => "123 Main St.",
                    "donor_city" => "My City",
                    "donor_state" => "NY",
                    "donor_email" => "name@example.com",
                    "compliance_employer" => "ACME employment",
                    "compliance_occupation" => "ACME occupation",
                    "source_code" => "12345",
                    "cc_first_name" => "John",
                    "cc_last_name" => "Smith",
                    "cc_number" => "4111111111111111",
                    "cc_verification_value" => "111",
                    "cc_zip" => "20001",
                    "cc_month" => "08",
                    "cc_year" => "2019",
                )
            )
        );
    }
}
