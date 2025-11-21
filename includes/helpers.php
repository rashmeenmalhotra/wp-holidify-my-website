<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get all holidays from option.
 *
 * @return array
 */
function holidify_get_holidays() {
    $holidays = get_option( 'holidify_settings', array() );
    if ( ! is_array( $holidays ) ) {
        $holidays = array();
    }
    return $holidays;
}

/**
 * Save all holidays.
 *
 * @param array $holidays
 */
function holidify_save_holidays( $holidays ) {
    if ( ! is_array( $holidays ) ) {
        $holidays = array();
    }
    update_option( 'holidify_settings', $holidays );
}

/**
 * Get active holiday ID.
 *
 * @return string|null
 */
function holidify_get_active_holiday_id() {
    $id = get_option( 'holidify_active_holiday', '' );
    return $id ? $id : '';
}

/**
 * Get active holiday array or null.
 *
 * @return array|null
 */
function holidify_get_active_holiday() {
    $id       = holidify_get_active_holiday_id();
    $holidays = holidify_get_holidays();

    if ( $id && isset( $holidays[ $id ] ) ) {
        return $holidays[ $id ];
    }

    return null;
}

/**
 * Basic sanitize for holiday item.
 *
 * @param array $data
 * @return array
 */
function holidify_sanitize_holiday( $data ) {
    $sanitized = array();

    $sanitized['name']       = isset( $data['name'] ) ? sanitize_text_field( $data['name'] ) : '';
    $sanitized['start_date'] = isset( $data['start_date'] ) ? sanitize_text_field( $data['start_date'] ) : '';
    $sanitized['end_date']   = isset( $data['end_date'] ) ? sanitize_text_field( $data['end_date'] ) : '';
    $sanitized['animation']  = isset( $data['animation'] ) ? sanitize_text_field( $data['animation'] ) : 'snowflakes';

    $icons = array();
    if ( isset( $data['icons'] ) && is_array( $data['icons'] ) ) {
        foreach ( $data['icons'] as $icon ) {
            if ( $icon !== '' ) {
                $icons[] = sanitize_text_field( $icon );
            }
        }
    }

    // Always keep exactly 3 slots (empty allowed)
    $icons = array_values( $icons );
    for ( $i = count( $icons ); $i < 3; $i++ ) {
        $icons[] = '';
    }

    $sanitized['icons']   = array_slice( $icons, 0, 3 );
    $sanitized['enabled'] = ! empty( $data['enabled'] ) ? 1 : 0;

    return $sanitized;
}
