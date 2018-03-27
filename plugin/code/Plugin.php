<?php

namespace DEWordpressPlugin;

class Plugin {
    const ID = 'democracy-engine';
    const NAME = 'Democracy Engine Donation Form';
    const VERSION = '0.0.1';

    protected $prefix = 'democracy_engine_';
    protected $options = array();

    protected $options_default = array(
        'deactivate_deletes_data' => 1,
        'domain' => 'clientstage-donate.democracyengine.com',
        'username' => 'username',
        'password' => 'password',
        'account_id' => -1,
        'recipient_id' => 'recipient',
        'unknown_error_message' => "Donation could not be processed. Please try again later.",
        'success_message' => "Thank you for your donation."
    );

    protected $option_name;
    protected $table_results;
    protected $client;
    protected $templates;

    public function __construct() {
        $this->initialize();

        //setup plugin short codes
        add_shortcode('democracy-engine-donation-form', array($this, 'render_donation_form'));
        
        //setup plugin ajax endpoints
        add_action('wp_ajax_nopriv_donation_form_ajax', array($this, 'donation_form_ajax'));
        add_action('wp_ajax_donation_form_ajax', array($this, 'donation_form_ajax'));

        //setup plugin scripts
        wp_register_style(self::ID, plugins_url('../assets/app.css',__FILE__ ));
        wp_register_script(self::ID, plugins_url('../assets/app.js',__FILE__ ));
        wp_enqueue_style(self::ID);
        wp_enqueue_script(self::ID);
        wp_localize_script(
            self::ID,
            'democracy_engine_plugin',
            array(
                'ajax_url' => admin_url('admin-ajax.php')
            )
        );

        if (is_admin()) {
            $admin = new \DEWordpressPlugin\Admin();

            $admin_menu = 'admin_menu';
            $admin_notices = 'admin_notices';
            $plugin_action_links = 'plugin_action_links_democracy-engine/democracy-engine.php';

            add_action($admin_menu, array(&$admin, 'admin_menu'));
            add_action('admin_init', array(&$admin, 'admin_init'));
            add_filter($plugin_action_links, array(&$admin, 'plugin_action_links'));

            register_activation_hook(__FILE__, array(&$admin, 'activate'));
            if ($this->options['deactivate_deletes_data']) {
                register_deactivation_hook(__FILE__, array(&$admin, 'deactivate'));
            }
        }
    }

    protected function initialize() {
        global $wpdb;

        $this->table_results = $wpdb->get_blog_prefix(0) . $this->prefix . 'results';

        $this->option_name = self::ID . '-options';

        $this->set_options();

        $this->templates = new \League\Plates\Engine(plugin_dir_path( __FILE__ ) . '/templates');
        $this->client = new \DEWordpressPlugin\DEApiClient(
            $this->options['domain'],
            $this->options['username'],
            $this->options['password'],
            $this->options['account_id'],
            $this->options['recipient_id']
        );
    }

    protected function set_options() {
        $options = get_option($this->option_name);
        if (!is_array($options)) {
            $options = array();
        }
        $this->options = array_merge($this->options_default, $options);
    }

    public function render_donation_form() {
        return $this->templates->render('components/donationForm', [
            'donation_amounts' => array(    //TODO: Make this a plugin setting
                5,
                15,
                27,
                50,
                75,
                100,
                250
            ),
            'countries' => \DEWordpressPlugin\Constants::COUNTRIES,
            'us_states' => \DEWordpressPlugin\Constants::US_STATES,
            'months'  => \DEWordpressPlugin\Constants::MONTHS,
            'years' => range(date('Y'), date('Y') + 12) //current year until 12 years from now
        ]);
    }

    public function donation_form_ajax() {
        $postData = json_decode(json_decode(file_get_contents('php://input')));

        try{
            $data = $this->client->createDonation(
                array(
                    "line_items" => array(
                        array(
                            "amount" => $this->getAmountFromDonationPost($postData),
                            "recipient_id" => $this->options['recipient_id']
                        )
                    ),
                    "donor_first_name" => $this->getValueFromPost($postData, "donation[first_name]"),
                    "donor_last_name" => $this->getValueFromPost($postData, "donation[last_name]"),
                    "donor_company_name" => $this->getValueFromPost($postData, ""),
                    "donor_address1" => $this->getValueFromPost($postData, "donation[billing_address_attributes][address1]"),
                    "donor_address2" => $this->getValueFromPost($postData, "donation[billing_address_attributes][address2]"),
                    "donor_city" => $this->getValueFromPost($postData, "donation[billing_address_attributes][city]"),
                    "donor_state" => $this->getValueFromPost($postData, "donation[billing_address_attributes][state]"),
                    "donor_zip" => $this->getValueFromPost($postData, "donation[billing_address_attributes][zip]"),
                    "donor_email" => $this->getValueFromPost($postData, "donation[email]"),
                    "donor_phone" => $this->getValueFromPost($postData, "donation[billing_address_attributes][phone_number]"),
                    "cc_number" => $this->getValueFromPost($postData, "donation[card_number]"),
                    "cc_verification_value" => $this->getValueFromPost($postData, "donation[card_verification]"),
                    "cc_month" => $this->getValueFromPost($postData, "donation[card_expires_on(2i)]"),
                    "cc_year" => $this->getValueFromPost($postData, "donation[card_expires_on(1i)]"),

                    "cc_first_name" => $this->getValueFromPost($postData, "donation[first_name]"), //taking this from the personal info? Normally one would want a seperate billing name. I'm assuming for donation like this, we'd probably want the person actually paying to match the person we're collecting data from... like to ensure donation limits and stuff? right?
                    "cc_last_name" => $this->getValueFromPost($postData, "donation[last_name]"),
                    "cc_zip" => $this->getValueFromPost($postData, "donation[billing_address_attributes][zip]"),

                    "compliance_employer" => $this->getValueFromPost($postData, "donation[employer]"),
                    "compliance_occupation" => $this->getValueFromPost($postData, "donation[occupation]"),

                    // I'm not collecting this. However, Phase one doesn't support corporate donations. So, I'm guessing this is okay.
                    "compliance_employer_address1" => $this->getValueFromPost($postData, ""),
                    "compliance_employer_address2" => $this->getValueFromPost($postData, ""),
                    "compliance_employer_city" => $this->getValueFromPost($postData, ""),
                    "compliance_employer_state" => $this->getValueFromPost($postData, ""),
                    "compliance_employer_zip" => $this->getValueFromPost($postData, ""),
                    "compliance_employer_country" => $this->getValueFromPost($postData, ""),

                    "email_opt_in" => true,
                    "is_corporate_contribution" => false,
                    "source_code" => "SRCABC"
                )
            );
            //Is there any value in storing this response? In theory this is stored on
            // DE's side, and honestly, I don't want to deal with storing this stuff
            // safely?
            //
            // But maybe it would be worth while storing like donation amount, whatever unique id
            // DE gives us back... and stuff like that so the plugin can show cool values and analytical
            // stuff in the future?
            //
            // This sounds like a future version kind of thing.
            // TODO: Issue-8

            wp_send_json_success(
                array(
                    "message" => $this->options['success_message']
                )
            );
        } catch (Exception $e) {
            //ugly catch all, but since this is the highest level
            // code... I don't feel bad. Let's catch whatever and
            // report something generic to the user.

            //log exception
            error_log(
                "Exception when trying the democracy engine api: " . $e->getMessage()
            );
            //send generic error
            wp_send_json_error(
                array(
                    "message" => $this->options['unknown_error_message']
                ),
                500
            );
        }
    }

    private function getAmountFromDonationPost($postData) {
        $amount = $this->getValueFromPost($postData, "donation[amount]");

        if($amount === "") {
            return $this->getValueFromPost($postData, "donation[amount_option]");
        }

        return $amount;
    }

    private function getValueFromPost($postData, $key) {
        if ($key) {
            if($postData) {
                foreach($postData as $pair) {
                    if($pair->name == $key) {
                        return $pair->value;
                    }
                }
            } else {
                //TODO: throw custom exception
            }

        }

        return "";
    }
}
