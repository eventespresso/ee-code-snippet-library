<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

//This function removes the promotions add-on call to auto process promotions when registrations are added via the admin and
//is useful if you want 'Codeless' promotions applied to registrations on the front-end, but not on those made by the admin.
function tw_remove_promotions_from_admin_registrations(){

    if ( is_admin() && ! (defined('DOING_AJAX') && DOING_AJAX) ) {
        remove_action(
            'FHEE__EE_Ticket_Selector__process_ticket_selections__before_redirecting_to_checkout',
            array( 'EED_Promotions', 'auto_process_promotions_in_cart' )
        );
    }

}
add_action('init', 'tw_remove_promotions_from_admin_registrations');