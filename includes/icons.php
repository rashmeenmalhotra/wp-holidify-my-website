<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * List of available emoji icons for picker.
 *
 * @return array
 */
function holidify_get_available_icons() {
    return array(
        // General celebration
        '🎉', '🎊', '✨', '🎈', '🎀', '🎁', '🌟', '⭐',
        // Seasons / nature
        '🌸', '🌹', '🌺', '🌻', '🌲', '🍁', '🍂', '🍃',
        // Food / party
        '🎂', '🧁', '🍰', '🍬', '🍭', '🍫', '🍾', '🥂',
        // Holidays
        '🎄', '🎅', '⛄', '🎃', '👻', '🦇', '🦃', '🪔',
        // Hearts / love
        '❤️', '💖', '💕', '💛', '💙',
        // Flags / misc
        '🇺🇸', '🇨🇦', '🇮🇳', '🎆', '🧨'
    );
}
