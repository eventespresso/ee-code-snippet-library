<?php
/**
 * Customizes the line item descriptions sent to gateways (for payment methods which send itemized payments,
 * like PayPal Pro, Standard, Express, and Authorize.net AIM)
 */

add_filter( 'FHEE__EE_Gateway___line_item_desc', 'mn_customize_line_item_desc_add_attendee_info', 10, 4);
function mn_customize_line_item_desc_add_attendee_info( $item_description, EE_Gateway $gateway, EE_Line_Item $line_item, EE_Payment $payment  ){
    //find this line item's registrations, by finding all attendees who have registrations for the same ticket
    $ticket = $line_item->ticket();
    if( ! $ticket instanceof EE_Ticket ) {
        return $item_description;
    }
    $attendees = EEM_Attendee::instance()->get_all(
        array(
            array(
                'Registration.TKT_ID' => $ticket->ID(),
                'Registration.TXN_ID' => $payment->get('TXN_ID')
            )
        )
    );
    $attendee_names = array();
    foreach( $attendees as $attendee ) {
        $attendee_names[ $attendee->ID() ] = $attendee->full_name();
    }
    return sprintf( esc_html__('For event "%1$s (ID %2$s)", attendee(s):%3$s', 'event_espresso'), $ticket->get_event_name(), implode(', ', $attendee_names ) );
}