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

//this file exists for wordpress to pull metadata, but since it's 2018 we're auto loading everything for the
// plugin and loading the namespaced / OO code. The actualy entry point is in src/php/Plugin.php
require_once('vendor/autoload.php');
new \DEWordpressPlugin\Plugin();
