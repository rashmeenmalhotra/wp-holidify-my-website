<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register plugin settings.
 */
function holidify_register_settings() {

    register_setting(
        'holidify_settings_group',
        'holidify_settings',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'holidify_sanitize_settings',
            'default'           => array(),
        )
    );

    register_setting(
        'holidify_settings_group',
        'holidify_active_holiday',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        )
    );
}
add_action( 'admin_init', 'holidify_register_settings' );

/**
 * Sanitize holidays array from settings API (fallback if used).
 *
 * @param mixed $value
 * @return array
 */
function holidify_sanitize_settings( $value ) {
    if ( ! is_array( $value ) ) {
        return array();
    }

    $sanitized = array();

    foreach ( $value as $id => $holiday ) {
        $id = sanitize_key( $id );
        $sanitized[ $id ] = holidify_sanitize_holiday( $holiday );
    }

    return $sanitized;
}
