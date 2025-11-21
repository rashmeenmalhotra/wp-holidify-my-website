<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue frontend assets if a holiday is active.
 */
function holidify_enqueue_frontend_assets() {
    $holiday = holidify_get_active_holiday();
    if ( ! $holiday ) {
        return;
    }

    wp_enqueue_style(
        'holidify-frontend',
        HOLIDIFY_URL . 'assets/css/frontend.css',
        array(),
        HOLIDIFY_VERSION
    );

    wp_enqueue_script(
        'holidify-frontend',
        HOLIDIFY_URL . 'assets/js/frontend.js',
        array(),
        HOLIDIFY_VERSION,
        true
    );

    wp_localize_script(
        'holidify-frontend',
        'holidifyData',
        array(
            'animation' => isset( $holiday['animation'] ) ? $holiday['animation'] : 'snowflakes',
            'icons'     => isset( $holiday['icons'] ) ? $holiday['icons'] : array(),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'holidify_enqueue_frontend_assets' );

/**
 * Output frontend HTML containers for floating icons + animation layer.
 */
function holidify_output_frontend_markup() {
    $holiday = holidify_get_active_holiday();
    if ( ! $holiday ) {
        return;
    }

    $icons = isset( $holiday['icons'] ) ? $holiday['icons'] : array();
    $icons = array_pad( $icons, 3, '' );
    ?>
    <!-- Holidify My Website -->
    <div id="holidify-icons-wrapper">
        <div class="holidify-floating-icon pos-1"><?php echo esc_html( $icons[0] ); ?></div>
        <div class="holidify-floating-icon pos-2"><?php echo esc_html( $icons[1] ); ?></div>
        <div class="holidify-floating-icon pos-3"><?php echo esc_html( $icons[2] ); ?></div>
    </div>
    <div id="holidifyAnimation" class="holidify-animation"></div>
    <?php
}
add_action( 'wp_footer', 'holidify_output_frontend_markup' );
