<?php

/**
 * Plugin Name: Democracy Engine Donation Form
 *
 * Description: A simple wordpress plugin to accept donations through democracy engine
 *
 * Plugin URI: https://github.com/nathanmentley/democracy-engine-wp-plugin
 * Version: 0.0.1
 * Author: Nathan Mentley
 * License: BSD 2-Clause
 * @package democracy-engine
 */

$GLOBALS['democracy_engine'] = new democracy_engine;

class democracy_engine {
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
    );

    protected $option_name;
    protected $table_results;


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
            require_once dirname(__FILE__) . '/admin.php';
            $admin = new democracy_engine_admin;

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
    }

    protected function set_options() {
        $options = get_option($this->option_name);
        if (!is_array($options)) {
            $options = array();
        }
        $this->options = array_merge($this->options_default, $options);
    }

    public function render_donation_form() {
        echo '<div>';
            echo '<p>content</p>';
        echo '</div>';
    }

    public function donation_form_ajax() {
        global $wpdb; // this is how you get access to the database

        wp_send_json_success(array(
            "stuff" => array()
        ));
    }
}
