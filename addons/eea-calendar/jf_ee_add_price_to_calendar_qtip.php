<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

/* grabs the datetime's first ticket's price and displays it in the Calendar tooltip */

function jf_ee_add_price_to_calendar_qtip( $text, $datetime ) {
    $text = $datetime->total_tickets_available_at_this_datetime();
    if ( $datetime instanceof EE_Datetime ) {
        $ticket = $datetime->get_first_related( 'Ticket' );
        if ( $ticket instanceof EE_Ticket ) {
            $price = $ticket->get_first_related( 'Price' );
            if ( $price instanceof EE_Price ) {
                $price_display = $price->pretty_price();
                $text .= '<br>Price:&nbsp;' . $price_display;
            }
        }
    }
    return $text;
}
add_filter( 'FHEE__EE_Calendar__tooltip_datetime_available_spaces', 'jf_ee_add_price_to_calendar_qtip', 10, 2 );