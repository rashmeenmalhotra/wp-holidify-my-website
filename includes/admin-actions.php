<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * --------------------------------------------------------------------
 * AJAX: Save (create or update) holiday
 * --------------------------------------------------------------------
 */
function holidify_ajax_save_holiday() {

    check_ajax_referer( 'holidify_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array(
            'message' => __( 'Not allowed.', 'holidify-my-website' )
        ) );
    }

    // Holiday ID (random string ID generated in JS)
    $id = isset( $_POST['id'] ) ? sanitize_key( wp_unslash( $_POST['id'] ) ) : '';

    if ( ! $id ) {
        wp_send_json_error( array(
            'message' => __( 'Invalid holiday ID.', 'holidify-my-website' )
        ) );
    }

    // Get existing holidays
    $holidays = holidify_get_holidays();
    if ( ! is_array( $holidays ) ) {
        $holidays = array();
    }

    // Sanitize all input fields
    $name      = sanitize_text_field( $_POST['name']       ?? '' );
    $greeting  = sanitize_text_field( $_POST['greeting']   ?? '' );
    $start     = sanitize_text_field( $_POST['start_date'] ?? '' );
    $end       = sanitize_text_field( $_POST['end_date']   ?? '' );
    $animation = sanitize_text_field( $_POST['animation']  ?? '' );

    // Icons (array of 3 emojis or text)
    $icons = isset( $_POST['icons'] ) && is_array( $_POST['icons'] )
        ? array_map( 'sanitize_text_field', $_POST['icons'] )
        : array( '', '', '' );

    // Save holiday data
    $holidays[ $id ] = array(
        'name'       => $name,
        'greeting'   => $greeting,
        'start_date' => $start,
        'end_date'   => $end,
        'icons'      => $icons,
        'animation'  => $animation,
    );

    // Save to DB
    holidify_save_holidays( $holidays );

    wp_send_json_success( array(
        'message' => __( 'Holiday saved.', 'holidify-my-website' ),
    ) );
}
add_action( 'wp_ajax_holidify_save_holiday', 'holidify_ajax_save_holiday' );



/**
 * --------------------------------------------------------------------
 * AJAX: Delete holiday
 * --------------------------------------------------------------------
 */
function holidify_ajax_delete_holiday() {

    check_ajax_referer( 'holidify_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array(
            'message' => __( 'Not allowed.', 'holidify-my-website' )
        ) );
    }

    // Holiday ID
    $id = isset( $_POST['id'] ) ? sanitize_key( wp_unslash( $_POST['id'] ) ) : '';

    if ( ! $id ) {
        wp_send_json_error( array(
            'message' => __( 'Invalid holiday ID.', 'holidify-my-website' )
        ) );
    }

    // Get existing holidays
    $holidays = holidify_get_holidays();
    if ( isset( $holidays[ $id ] ) ) {
        unset( $holidays[ $id ] );
        holidify_save_holidays( $holidays );
    }

    wp_send_json_success( array(
        'message' => __( 'Holiday deleted.', 'holidify-my-website' ),
    ) );
}
add_action( 'wp_ajax_holidify_delete_holiday', 'holidify_ajax_delete_holiday' );



/**
 * --------------------------------------------------------------------
 * AJAX: Disable Holiday Mode (Pause)
 * --------------------------------------------------------------------
 */
function holidify_ajax_pause_holiday_mode() {
    check_ajax_referer( 'holidify_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'Not allowed.', 'holidify-my-website' ) ) );
    }

    update_option( 'holidify_paused', true );

    wp_send_json_success( array(
        'message' => __( 'Holiday mode paused.', 'holidify-my-website' ),
    ) );
}
add_action( 'wp_ajax_holidify_pause_holiday_mode', 'holidify_ajax_pause_holiday_mode' );



/**
 * --------------------------------------------------------------------
 * AJAX: Resume Holiday Mode
 * --------------------------------------------------------------------
 */
function holidify_ajax_resume_holiday_mode() {
    check_ajax_referer( 'holidify_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'Not allowed.', 'holidify-my-website' ) ) );
    }

    update_option( 'holidify_paused', false );

    wp_send_json_success( array(
        'message' => __( 'Holiday mode resumed.', 'holidify-my-website' ),
    ) );
}
add_action( 'wp_ajax_holidify_resume_holiday_mode', 'holidify_ajax_resume_holiday_mode' );
