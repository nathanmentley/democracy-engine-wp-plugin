<?php

class democracy_engine_admin extends democracy_engine {
    protected $capability_required;
    protected $fields;
    protected $form_action;
    protected $page_options;
    protected $settings;
    protected $text_settings;

    public function __construct() {
        $this->initialize();
        $this->set_sections();
        $this->set_fields();

        // Translation already in WP combined with plugin's name.
        $this->text_settings = self::NAME . ' ' . __('Settings');

        $this->capability_required = 'manage_options';
        $this->form_action = 'options.php';
        $this->page_options = 'options-general.php';
    }

    public function activate() {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sql = "CREATE TABLE `$this->table_results` (
                login_id BIGINT(20) NOT NULL AUTO_INCREMENT,
                user_login VARCHAR(60) NOT NULL DEFAULT '',
                date_login TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY  (login_id),
                KEY user_login (user_login(5))
                )";

        dbDelta($sql);
        if ($wpdb->last_error) {
            die($wpdb->last_error);
        }

        update_option($this->option_name, $this->options);
    }

    public function deactivate() {
        global $wpdb;

        $prior_error_setting = $wpdb->show_errors;
        $wpdb->show_errors = false;
        $denied = 'command denied to user';

        $wpdb->query("DROP TABLE `$this->table_results`");
        if ($wpdb->last_error) {
            if (strpos($wpdb->last_error, $denied) === false) {
                die($wpdb->last_error);
            }
        }

        $wpdb->show_errors = $prior_error_setting;

        $package_id = self::ID;
        $wpdb->escape_by_ref($package_id);

        $wpdb->query("DELETE FROM `$wpdb->options`
                WHERE option_name LIKE '$package_id%'");

        $wpdb->query("DELETE FROM `$wpdb->usermeta`
                WHERE meta_key LIKE '$package_id%'");
    }

    protected function set_sections() {
        $this->sections = array(
            'login' => array(
                'title' => __("Democracy Engine Login Info", 'democracy-engine'),
                'callback' => 'section_login',
            )
        );
    }

    protected function set_fields() {
        $this->fields = array(
            'domain' => array(
                'section' => 'login',
                'label' => __("Domain", 'democracy-engine'),
                'text' => __("Democracy Engine Domain Name", 'democracy-engine'),
                'type' => 'string',
            ),
            'username' => array(
                'section' => 'login',
                'label' => __("Username", 'democracy-engine'),
                'text' => __("Democracy Engine Username", 'democracy-engine'),
                'type' => 'string',
            ),
            'password' => array(
                'section' => 'login',
                'label' => __("Password", 'democracy-engine'),
                'text' => __("Democracy Engine Password", 'democracy-engine'),
                'type' => 'string',
            ),
            'deactivate_deletes_data' => array(
                'section' => 'login',
                'label' => __("Deactivation", 'democracy-engine'),
                'text' => __("Should deactivating the plugin remove all of the plugin's data and settings?", 'democracy-engine'),
                'type' => 'bool',
                'bool0' => __("No, preserve the data for future use.", 'democracy-engine'),
                'bool1' => __("Yes, delete the data.", 'democracy-engine'),
            )
        );
    }

    public function plugin_action_links($links) {
        // Translation already in WP.
        $links[] = '<a href="' . $this->hsc_utf8($this->page_options)
                . '?page=' . self::ID . '">'
                . $this->hsc_utf8(__('Settings')) . '</a>';
        return $links;
    }

    public function admin_menu() {
        add_submenu_page(
            $this->page_options,
            $this->text_settings,
            self::NAME,
            $this->capability_required,
            self::ID,
            array(&$this, 'page_settings')
        );
    }

    public function admin_init() {
        register_setting(
            $this->option_name,
            $this->option_name,
            array(&$this, 'validate')
        );

        // Dynamically declares each section using the info in $sections.
        foreach ($this->sections as $id => $section) {
            add_settings_section(
                self::ID . '-' . $id,
                $this->hsc_utf8($section['title']),
                array(&$this, $section['callback']),
                self::ID
            );
        }

        // Dynamically declares each field using the info in $fields.
        foreach ($this->fields as $id => $field) {
            add_settings_field(
                $id,
                $this->hsc_utf8($field['label']),
                array(&$this, $id),
                self::ID,
                self::ID . '-' . $field['section']
            );
        }
    }

    public function page_settings() {
        echo '<h2>' . $this->hsc_utf8($this->text_settings) . '</h2>';
        echo '<form action="' . $this->hsc_utf8($this->form_action) . '" method="post">' . "\n";
        settings_fields($this->option_name);
        do_settings_sections(self::ID);
        submit_button();
        echo '</form>';
    }

    public function section_login() {
        echo '<p>';
        echo $this->hsc_utf8(__("Democracy Engine API Login Info", 'democracy-engine'));
        echo '</p>';
    }

    public function __call($name, $params) {
        if (empty($this->fields[$name]['type'])) {
            return;
        }
        switch ($this->fields[$name]['type']) {
            case 'bool':
                $this->input_radio($name);
                break;
            case 'int':
                $this->input_int($name);
                break;
            case 'string':
                $this->input_string($name);
                break;
        }
    }

    protected function input_radio($name) {
        echo $this->hsc_utf8($this->fields[$name]['text']) . '<br/>';
        echo '<input type="radio" value="0" name="'
            . $this->hsc_utf8($this->option_name)
            . '[' . $this->hsc_utf8($name) . ']"'
            . ($this->options[$name] ? '' : ' checked="checked"') . ' /> ';
        echo $this->hsc_utf8($this->fields[$name]['bool0']);
        echo '<br/>';
        echo '<input type="radio" value="1" name="'
            . $this->hsc_utf8($this->option_name)
            . '[' . $this->hsc_utf8($name) . ']"'
            . ($this->options[$name] ? ' checked="checked"' : '') . ' /> ';
        echo $this->hsc_utf8($this->fields[$name]['bool1']);
    }

    protected function input_int($name) {
        echo '<input type="text" size="3" name="'
            . $this->hsc_utf8($this->option_name)
            . '[' . $this->hsc_utf8($name) . ']"'
            . ' value="' . $this->hsc_utf8($this->options[$name]) . '" /> ';
        echo $this->hsc_utf8($this->fields[$name]['text']
                . ' ' . __('Default:', 'democracy-engine') . ' '
                . $this->options_default[$name] . '.');
    }

    protected function input_string($name) {
        echo '<input type="text" size="75" name="'
            . $this->hsc_utf8($this->option_name)
            . '[' . $this->hsc_utf8($name) . ']"'
            . ' value="' . $this->hsc_utf8($this->options[$name]) . '" /> ';
        echo '<br />';
        echo $this->hsc_utf8($this->fields[$name]['text']
                . ' ' . __('Default:', 'democracy-engine') . ' '
                . $this->options_default[$name] . '.');
    }

    public function validate($in) {
        $out = $this->options_default;
        if (!is_array($in)) {
            // Not translating this since only hackers will see it.
            add_settings_error($this->option_name,
                    $this->hsc_utf8($this->option_name),
                    'Input must be an array.');
            return $out;
        }

        $gt_format = __("must be >= '%s',", 'democracy-engine');
        $default = __("so we used the default value instead.", 'democracy-engine');

        // Dynamically validate each field using the info in $fields.
        foreach ($this->fields as $name => $field) {
            if (!array_key_exists($name, $in)) {
                continue;
            }

            if (!is_scalar($in[$name])) {
                // Not translating this since only hackers will see it.
                add_settings_error($this->option_name,
                        $this->hsc_utf8($name),
                        $this->hsc_utf8("'" . $field['label'])
                                . "' was not a scalar, $default");
                continue;
            }

            switch ($field['type']) {
                case 'bool':
                    if ($in[$name] != 0 && $in[$name] != 1) {
                        // Not translating this since only hackers will see it.
                        add_settings_error($this->option_name,
                                $this->hsc_utf8($name),
                                $this->hsc_utf8("'" . $field['label']
                                        . "' must be '0' or '1', $default"));
                        continue 2;
                    }
                    break;
                case 'int':
                    if (!ctype_digit($in[$name])) {
                        add_settings_error($this->option_name,
                                $this->hsc_utf8($name),
                                $this->hsc_utf8("'" . $field['label'] . "' "
                                        . __("must be an integer,", 'democracy-engine')
                                        . ' ' . $default));
                        continue 2;
                    }
                    if (array_key_exists('greater_than', $field)
                        && $in[$name] < $field['greater_than'])
                    {
                        add_settings_error($this->option_name,
                                $this->hsc_utf8($name),
                                $this->hsc_utf8("'" . $field['label'] . "' "
                                        . sprintf($gt_format, $field['greater_than'])
                                        . ' ' . $default));
                        continue 2;
                    }
                    break;
            }
            $out[$name] = $in[$name];
        }

        return $out;
    }

    protected function hsc_utf8($in) {
        return htmlspecialchars($in, ENT_QUOTES, 'UTF-8');
    }

    protected function sanitize_whitespace($in) {
        return preg_replace('/\s+/u', ' ', $in);
    }
}
