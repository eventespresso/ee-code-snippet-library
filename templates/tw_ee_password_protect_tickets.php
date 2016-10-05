<?php
/*
Usage: Create a password protected post or page
Add the Ticket Selector shortcode for your event to the page
Then use that post ID for $VIP_Post_ID
Then use your ticket ID for $VIP_Ticket_ID;
The VIP ticket is not displayed on all posts except the password protected VIP post
Non-VIP tickets are not displayed on VIP post
*/
function tw_espresso_vip_tickets( $ticket_row_html, EE_Ticket $ticket ) {
    global $post;

    // Array of password protected posts or pages
    $VIP_Post_IDs = array(
    	14,
    );
    
    //Array of ticket IDs for the restricted access tickets
    $VIP_Ticket_IDs = array( 
    	6,
    	7,
    	8,
    );

    //Check if the current post ID is within 'VIP_Post_IDs', if so you on a protected page that should display the ticket that have ID's matching those set within $VIP_Ticket_ID's
    if ( $post instanceof WP_Post && ( ( $post->ID != $VIP_Post_IDs && in_array( $ticket->ID(), $VIP_Ticket_IDs ) ) || ( $post->ID == $VIP_Post_IDs && ! in_array( $ticket->ID(), $VIP_Ticket_IDs ) ) ) ) {
        return '';
    }
    return $ticket_row_html;
}
add_filter( 'FHEE__ticket_selector_chart_template__do_ticket_entire_row', 'tw_espresso_vip_tickets', 10, 2 );