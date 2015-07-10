<?php
/*
Usage: Create a password protected post or page
Add the Ticket Selector shortcode for your event to the page
Then use that post ID for $VIP_Post_ID
Then use your ticket ID for $VIP_Ticket_ID;
The VIP ticket is not displayed on all posts except the password protected VIP post
Non-VIP tickets are not displayed on VIP post
*/
function espresso_vip_tickets( $ticket_row_html, EE_Ticket $ticket ) {
    global $post;
    $VIP_Post_ID = 12345; // The password protected post or page
    $VIP_Ticket_ID = 374; // The ticket ID of the restricted access ticket
    if ( $post instanceof WP_Post && ( ( $post->ID != $VIP_Post_ID && $ticket->ID() == $VIP_Ticket_ID ) || ( $post->ID == $VIP_Post_ID && $ticket->ID() != $VIP_Ticket_ID ) ) ) {
        return '';
    }
    return $ticket_row_html;
}
add_filter( 'FHEE__ticket_selector_chart_template__do_ticket_entire_row', 'espresso_vip_tickets', 10, 2 );
