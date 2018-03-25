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
                    "donor_first_name" => "Foo",
                    "donor_last_name" => "Grits",
                    "donor_company_name" => "",
                    "donor_address1" => "452 Any St.",
                    "donor_address2" => "",
                    "donor_city" => "Wind Farm",
                    "donor_state" => "AL",
                    "donor_zip" => "34166",
                    "donor_email" => "Khomas@pll.com",
                    "donor_phone" => "",
                    "cc_number" => "4111111111111111",
                    "cc_verification_value" => "123",
                    "cc_month" => 12,
                    "cc_year" => 2019,
                    "cc_first_name" => "Dan",
                    "cc_last_name" => "Miller",
                    "cc_zip" => "97035",
                    "compliance_employer" => "ACME employment",
                    "compliance_occupation" => "ACME occupation",
                    "compliance_employer_address1" => "Bradford Building, 14th Floor",
                    "compliance_employer_address2" => "123 Main St",
                    "compliance_employer_city" => "Town",
                    "compliance_employer_state" => "IL",
                    "compliance_employer_zip" => "0l337",
                    "compliance_employer_country" => "US",
                    "email_opt_in" => true,
                    "is_corporate_contribution" => false,
                    "source_code" => "SRCABC"
                )
            )
        );
    }
}
