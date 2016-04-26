<?php
/**
 * Code snippet that extends the Event Espresso WP User integration addon
 * to poplulate the user's stored System Address questions too
 */

add_filter( 'FHEE__EEM_Answer__get_attendee_question_answer_value__answer_value', 'jf_filter_address_answer_for_wpuser', 10, 4 );

function jf_filter_address_answer_for_wpuser( $value, EE_Registration $registration, $question_id, $system_id = null ) {
    if( class_exists( 'EED_WP_Users_SPCO' ) ) {
        global $current_user;
        $attendee = EED_WP_Users_SPCO::get_attendee_for_user( $current_user );
    } else {
        $attendee = null;
    } 
    //only fill for primary registrant
    if ( ! $registration->is_primary_registrant() ) {
        return $value;
    }
    if ( empty($value) ) {
        if( is_numeric( $question_id ) && 
        defined( 'EEM_Attendee::system_question_address' ) ) {
            $address = EEM_Attendee::system_question_address;
            $address2 = EEM_Attendee::system_question_address2;
            $city = EEM_Attendee::system_question_city;
            $state = EEM_Attendee::system_question_state;
            $country = EEM_Attendee::system_question_country;
            $zip = EEM_Attendee::system_question_zip;
            $phone = EEM_Attendee::system_question_phone;
            $id_to_use = $system_id;
        } 
        if ( $current_user instanceof WP_User && 
        $attendee instanceof EE_Attendee ) {
            switch ( $id_to_use ) {
                case $address :
                    $value = $attendee->get( 'ATT_address' );
                    break;
                case $address2 :
                    $value = $attendee->get( 'ATT_address2' );
                    break;
                case $city :
                   $value = $attendee->get( 'ATT_city' );
                    break;
                case $country :
                   $value = $attendee->get( 'CNT_ISO' );
                    break;
                case $state :
                   $value = $attendee->get( 'STA_ID' );
                    break;
                case $zip :
                    $value = $attendee->get( 'ATT_zip' );
                    break;
                case $phone :
                    $value = $attendee->get( 'ATT_phone' );
                    break;
                default:
            }
        }
    }
    return $value;
}