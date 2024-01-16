<?php
/* 
 * Modifies the EE Event Waitlist form to set a default value of 100 for Waitlist spaces and enable auto promotion
 */
add_filter('FHEE__EE_Base_Class__get_extra_meta__default_value', 'tw_ee_set_default_waitlist_values', 10, 3);
function tw_ee_set_default_waitlist_values( $current_default, $meta_key, $single) {

    // Set the default value for EE Waitlist registrations to 100
    if( $meta_key === 'ee_wait_list_spaces') {
        return 100;
    }
    // Enable Waitlist Auto Promotion by default
    if( $meta_key === 'ee_wait_list_auto_promote') {
        return true;
    }

    return $current_default;
}