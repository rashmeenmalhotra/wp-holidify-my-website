<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue admin assets only on plugin page.
 */
function holidify_enqueue_admin_assets( $hook ) {
    if ( $hook !== 'toplevel_page_holidify-my-website' ) {
        return;
    }

    wp_enqueue_style(
        'holidify-admin',
        HOLIDIFY_URL . 'assets/css/admin.css',
        array(),
        HOLIDIFY_VERSION
    );

    wp_enqueue_script(
        'holidify-admin',
        HOLIDIFY_URL . 'assets/js/admin.js',
        array( 'jquery' ),
        HOLIDIFY_VERSION,
        true
    );

    $icons = holidify_get_available_icons();

    wp_localize_script(
        'holidify-admin',
        'holidifyAdmin',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'holidify_nonce' ),
            'icons'    => array_values( $icons ),
        )
    );
}
add_action( 'admin_enqueue_scripts', 'holidify_enqueue_admin_assets' );
