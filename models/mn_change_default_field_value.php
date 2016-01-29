<?php

/**
 * Changes the event's default value for displaying the ticket selector.
 * Any model's fields' default values can be changed using similar code snippets.
 * Only works with EE 4.8.28 or higher
 * @param mixed $default_value
 * @param EE_Model_Field_Base $field_obj
 */
function ee_change_ticket_selector_default( $default_value, $field_obj ) {
    if( $field_obj instanceof EE_Model_Field_Base 
        && $field_obj->get_model_name() == 'Event' 
        && $field_obj->get_name() == 'EVT_display_ticket_selector' ) {
        return false;
    }
    return $default_value;
}
add_filter( 'FHEE__EE_Model_Field_Base___construct_finalize___default_value', 'ee_change_ticket_selector_default', 10, 2 );