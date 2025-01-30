<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

// Change default for "Trigger Notifications?" to No on moving a registration
add_action('FHEE__EE_Admin_Page___load_page_dependencies__after_load__espresso_registrations__edit_attendee_selections', function() {
    add_filter( 'FHEE__EE_Form_Section_Proper___construct__options_array', 'tw_ee_change_attendee_mover_trigger_notifications_default', 10, 2 );
});
function tw_ee_change_attendee_mover_trigger_notifications_default( $options, $form ) {
    if( $form instanceof EE_Form_Section_Proper 
        && isset( $options[ 'name' ] ) 
        && $options[ 'name' ] === 'verify_changes' 
    ) {
        $notifications_subsection   = isset($options[ 'subsections' ][ 'notifications' ]) ? $options[ 'subsections' ][ 'notifications' ] : null;
        if($notifications_subsection instanceof EE_Form_Section_Proper) {
            $trigger_send_inptut        = $notifications_subsection->get_subsection('trigger_send');
            if( $trigger_send_inptut instanceof EE_Yes_No_Input ) {
                $trigger_send_inptut->set_default( false );
            }
        }
    }
    return $options;
}