<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file
//
// You can add this code to make the Scanner UI's frontend event dropdown list consistent with what would show in the backend

add_filter( 
    'FHEE__EED_Barcode_Scanner__scanner_form__event_query', 
    'ee_modify_barcode_scanner_form_event_query' 
);
function ee_modify_barcode_scanner_form_event_query( $query ) {
    $query['caps'] = EEM_Event::caps_read_admin;
    return $query;
}