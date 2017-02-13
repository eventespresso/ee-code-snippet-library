<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

//This function displays a ninja form for sold out events to be used as a waitlist.
function ee_espresso_clean_event_status( $EVT_ID ) {
    $event = EEH_Event_View::get_event( $EVT_ID );
    $status = $event instanceof EE_Event ? $event->get_active_status() : 'inactive';
    return $status;
}

function ee_special_sold_out_message( $post ) {
    
    //Set the ID of the Ninja Form you wish to call here.
    $ninja_forms_id = 1;

    //Check if the event is sold out.
    if ( ee_espresso_clean_event_status( $post->ID ) == 'DTS' ) {
        if( method_exists( Ninja_Forms(), 'display') ) {
            //Using Ninja Forms v3+
            Ninja_Forms()->display( $ninja_forms_id );
        } elseif( function_exists( 'ninja_forms_display_form' ) ) { 
            //Using a previous version of Ninja Forms
            ninja_forms_display_form( $ninja_forms_id ); 
        }
    }
}
add_action( 'AHEE_event_details_after_the_content', 'ee_special_sold_out_message' );