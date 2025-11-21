<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AJAX: Save (create or update) holiday.
 */
function holidify_ajax_save_holiday() {
    check_ajax_referer( 'holidify_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'Not allowed.', 'holidify-my-website' ) ) );
    }

    $id = isset( $_POST['id'] ) ? sanitize_key( wp_unslash( $_POST['id'] ) ) : '';

    if ( ! $id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid holiday ID.', 'holidify-my-website' ) ) );
    }

    $holidays = holidify_get_holidays();

    $data = array(
        'name'       => isset( $_POST['name'] ) ? wp_unslash( $_POST['name'] ) : '',
        'start_date' => isset( $_POST['start_date'] ) ? wp_unslash( $_POST['start_date'] ) : '',
        'end_date'   => isset( $_POST['end_date'] ) ? wp_unslash( $_POST['end_date'] ) : '',
        'animation'  => isset( $_POST['animation'] ) ? wp_unslash( $_POST['animation'] ) : '',
        'icons'      => isset( $_POST['icons'] ) ? (array) $_POST['icons'] : array(),
    );

    $holidays[ $id ] = holidify_sanitize_holiday( $data );
    holidify_save_holidays( $holidays );

    wp_send_json_success(
        array(
            'message' => __( 'Holiday saved.', 'holidify-my-website' ),
        )
    );
}
add_action( 'wp_ajax_holidify_save_holiday', 'holidify_ajax_save_holiday' );

/**
 * AJAX: Delete holiday.
 */
function holidify_ajax_delete_holiday() {
    check_ajax_referer( 'holidify_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'Not allowed.', 'holidify-my-website' ) ) );
    }

    $id = isset( $_POST['id'] ) ? sanitize_key( wp_unslash( $_POST['id'] ) ) : '';

    if ( ! $id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid holiday ID.', 'holidify-my-website' ) ) );
    }

    $holidays = holidify_get_holidays();

    if ( isset( $holidays[ $id ] ) ) {
        unset( $holidays[ $id ] );
        holidify_save_holidays( $holidays );

        // Clear active if this was active
        $active_id = holidify_get_active_holiday_id();
        if ( $active_id === $id ) {
            update_option( 'holidify_active_holiday', '' );
        }
    }

    wp_send_json_success(
        array(
            'message' => __( 'Holiday deleted.', 'holidify-my-website' ),
        )
    );
}
add_action( 'wp_ajax_holidify_delete_holiday', 'holidify_ajax_delete_holiday' );

/**
 * AJAX: Set active holiday.
 */
function holidify_ajax_set_active_holiday() {
    check_ajax_referer( 'holidify_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'Not allowed.', 'holidify-my-website' ) ) );
    }

    $id = isset( $_POST['id'] ) ? sanitize_key( wp_unslash( $_POST['id'] ) ) : '';

    if ( $id ) {
        update_option( 'holidify_active_holiday', $id );
    } else {
        update_option( 'holidify_active_holiday', '' );
    }

    wp_send_json_success(
        array(
            'message' => __( 'Active holiday updated.', 'holidify-my-website' ),
        )
    );
}
add_action( 'wp_ajax_holidify_set_active_holiday', 'holidify_ajax_set_active_holiday' );
