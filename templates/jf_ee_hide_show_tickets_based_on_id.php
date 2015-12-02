<?php
/*
Variation of: https://github.com/eventespresso/ee-code-snippet-library/blob/master/templates/jf_ee_password_protect_ticket.php
Usage: Create a password protected post or page
Add the Ticket Selector shortcode for your event to the page
Then use that post ID for $VIP_Post_ID
Then use your ticket ID for $VIP_Ticket_ID;
The VIP ticket is not displayed on all posts except the password protected VIP post
The non-VIP ticket is not displayed on VIP post
*/

function jf_ee_hide_show_tickets_based_on_id( $ticket_row_html, EE_Ticket $ticket ) {
    global $post;
    // The password protected post or page
    $VIP_Post_ID = 51830; 
    // The ticket ID of the restricted access ticket
    $VIP_Ticket_ID = 591; 
    // The ticket ID of the regular priced ticket that will **not** appear on the password protected page
    $non_VIP_Ticket_ID = 578; 
    // Hide the restricted access ticket on the regular event page and
    // Hide the standard price ticket on the password protected page
    if ( $post instanceof WP_Post && 
        ( ( $post->ID != $VIP_Post_ID && $ticket->ID() == $VIP_Ticket_ID ) || 
            ( $post->ID == $VIP_Post_ID && $ticket->ID() == $non_VIP_Ticket_ID ) ) ) {
        return '';
    }
    return $ticket_row_html;
}
add_filter( 'FHEE__ticket_selector_chart_template__do_ticket_entire_row', 'jf_ee_hide_show_tickets_based_on_id', 10, 2 );