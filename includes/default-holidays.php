<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Default holidays for first-time activation.
 *
 * @return array
 */
function holidify_get_default_holidays() {

    $year = date( 'Y' );

    return array(

        'christmas' => array(
            'name'       => 'Christmas',
            'greeting'   => 'Merry Christmas 🎄',
            'start_date' => $year . '-12-20',
            'end_date'   => $year . '-12-26',
            'icons'      => array( '🎄', '🎅', '⛄' ),
            'animation'  => 'snowflakes',
            'enabled'    => 0,
        ),

        'halloween' => array(
            'name'       => 'Halloween',
            'greeting'   => 'Happy Halloween 🎃',
            'start_date' => $year . '-10-25',
            'end_date'   => $year . '-10-31',
            'icons'      => array( '🎃', '👻', '🦇' ),
            'animation'  => 'bats',
            'enabled'    => 0,
        ),

        'thanksgiving' => array(
            'name'       => 'Thanksgiving',
            'greeting'   => 'Happy Thanksgiving 🦃',
            'start_date' => $year . '-11-24',
            'end_date'   => $year . '-11-28',
            'icons'      => array( '🦃', '🍂', '🌽' ),
            'animation'  => 'leaves',
            'enabled'    => 0,
        ),

        'new_year' => array(
            'name'       => 'New Year',
            'greeting'   => 'Happy New Year 🎉',
            'start_date' => $year . '-12-31',
            'end_date'   => ( $year + 1 ) . '-01-02',
            'icons'      => array( '🎉', '🎆', '🥂' ),
            'animation'  => 'confetti',
            'enabled'    => 0,
        ),

        'independence_us' => array(
            'name'       => 'US Independence Day',
            'greeting'   => 'Happy 4th of July 🇺🇸',
            'start_date' => $year . '-07-02',
            'end_date'   => $year . '-07-05',
            'icons'      => array( '🇺🇸', '🎆', '🗽' ),
            'animation'  => 'fireworks',
            'enabled'    => 0,
        ),

        'canada_day' => array(
            'name'       => 'Canada Day',
            'greeting'   => 'Happy Canada Day 🇨🇦',
            'start_date' => $year . '-06-30',
            'end_date'   => $year . '-07-02',
            'icons'      => array( '🇨🇦', '🍁', '🎆' ),
            'animation'  => 'maple',
            'enabled'    => 0,
        ),
    );
}
