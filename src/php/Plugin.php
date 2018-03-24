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
        'domain' => 'clientstage-api.democracyengine.com',
        'username' => 'username',
        'password' => 'password',
        'account_id' => -1
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
        add_action('wp_ajax_democracy_engine_donation_form', array($this, 'donation_form_ajax'));

        //setup plugin scripts
        wp_register_style(self::ID, plugins_url('assets/app.css',__FILE__ ));
        wp_register_script(self::ID, plugins_url('assets/app.js',__FILE__ ));
        wp_enqueue_style(self::ID);
        wp_enqueue_script(self::ID);

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
            $this->options['account_id']
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
        echo $this->templates->render('donationForm', [
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
            'years' => range(date('Y'), date('Y') + 12),
            'months'  => \DEWordpressPlugin\Constants::MONTHS
        ]);
    }

    public function donation_form_ajax() {
        global $wpdb; // this is how you get access to the database

        wp_send_json_success(array(
            "stuff" => array()
        ));
    }
}
