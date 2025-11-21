<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add admin menu page.
 */
function holidify_add_admin_menu() {
    add_menu_page(
        __( 'Holidify My Website', 'holidify-my-website' ),
        __( 'Holidify', 'holidify-my-website' ),
        'manage_options',
        'holidify-my-website',
        'holidify_render_admin_page',
        'dashicons-calendar-alt',
        65
    );
}
add_action( 'admin_menu', 'holidify_add_admin_menu' );
