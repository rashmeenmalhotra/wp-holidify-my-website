<?php
/**
 * Plugin Name: Holidify My Website
 * Plugin URI: https://example.com/holidify-my-website
 * Description: Transform your website with festive themes for holidays using custom floating icons and subtle animations.
 * Version: 1.0.0
 * Author: BragDeal Inc. 
 * Author URI: https://BragDeal.com
 * License: GPL v2 or later
 * Text Domain: holidify-my-website
 */

/**
 * Security check  
 * Prevents direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Plugin constants  
 * Useful for paths and URLs used throughout the plugin.
 */
define( 'HOLIDIFY_VERSION', '1.0.0' );
define( 'HOLIDIFY_DIR', plugin_dir_path( __FILE__ ) );
define( 'HOLIDIFY_URL', plugin_dir_url( __FILE__ ) );

/**
 * Required include files  
 * Each file handles a specific part of the plugin.
 */
require_once HOLIDIFY_DIR . 'includes/helpers.php';
require_once HOLIDIFY_DIR . 'includes/default-holidays.php';
require_once HOLIDIFY_DIR . 'includes/icons.php';
require_once HOLIDIFY_DIR . 'includes/settings.php';
require_once HOLIDIFY_DIR . 'includes/admin-menu.php';
require_once HOLIDIFY_DIR . 'includes/admin-page.php';
require_once HOLIDIFY_DIR . 'includes/admin-actions.php';
require_once HOLIDIFY_DIR . 'includes/frontend.php';
require_once HOLIDIFY_DIR . 'includes/assets.php';

/**
 * Plugin activation  
 * Runs once when the plugin is activated.  
 * Loads default holiday settings if it's a fresh install.
 */


function holidify_activate() {

    $existing_settings = get_option( 'holidify_settings', array() );

    if ( empty( $existing_settings ) ) {
        $defaults = holidify_get_default_holidays();
        update_option( 'holidify_settings', $defaults );
    }
}

register_activation_hook( __FILE__, 'holidify_activate' );
