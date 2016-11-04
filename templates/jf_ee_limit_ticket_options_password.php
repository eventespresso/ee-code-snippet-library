<?php
/*
Usage: Create a password protected post or page
Add the Ticket Selector shortcode for your event to the page
Then use that post ID for $VIP_Post_ID
Then use your ticket ID for $VIP_Ticket_ID
The VIP ticket quantity selector will not be displayed except on the password protected VIP post.
Your custom message (from line 23 below) will display instead.
*/

add_filter( 'FHEE__ticket_selector_chart_template__do_ticket_inside_row', 'jf_ee_limit_ticket_options_password', 10, 2 );
function jf_ee_limit_ticket_options_password( $return_value, EE_Ticket $ticket ) {
    global $post;
    $VIP_Post_ID = 62; // The password protected post or page
    $VIP_Ticket_ID = 3; // The ticket ID of the restricted access ticket
    if ( $ticket->ID() != $VIP_Ticket_ID ) {
        return false;
    }
    if ( $post instanceof WP_Post && ( $post->ID == $VIP_Post_ID ) ) {
        return false;
    }
    $return_value = '<td class="tckt-slctr-tbl-td-name" colspan="3">';
    $inner_message = sprintf( 'The %s requires permission.', $ticket->name() );
    $return_value .= $inner_message . '</td>';
    return $return_value; 
}