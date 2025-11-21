<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Show greeting board on frontend
 */
function holidify_render_greeting_board() {

    if ( is_admin() ) {
        return;
    }

    if ( get_option( 'holidify_paused', false ) ) {
        return;
    }

    $holidays = holidify_get_holidays();
    $active_id = holidify_get_active_holiday_based_on_date( $holidays );

    if ( ! $active_id || empty( $holidays[ $active_id ] ) ) {
        return;
    }

    $holiday = $holidays[ $active_id ];

    // Greeting fallback
    $greeting = $holiday['greeting'] ?? '';
    if ( $greeting === '' ) {
        $greeting = 'Happy ' . ( $holiday['name'] ?? '' ) . ' 🎉';
    }
    ?>
    <div class="holidify-greeting-board">
        <span class="holidify-greeting-emoji">🎉</span>
        <span class="holidify-greeting-text"><?php echo esc_html( $greeting ); ?></span>
    </div>
    <?php
}

add_action( 'wp_footer', 'holidify_render_greeting_board' );


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
