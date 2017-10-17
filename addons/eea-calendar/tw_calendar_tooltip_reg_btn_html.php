<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

//This is an example of how you can change the register now button within the calendar based on the event/datetime status.
//This function will return the register now button for sold out events.
function tw_calendar_tooltip_reg_btn_html( $current_btn_html, $event, $datetime ) {

    if ($event->is_sold_out()
        || $datetime->sold_out()
        || $datetime->total_tickets_available_at_this_datetime() === 0
    ) {
        return '<div class="sold-out-dv"><a href="' . apply_filters( 'FHEE__EE_Calendar__tooltip_event_permalink', $event->get_permalink(), $event, $datetime ) . '">' . __('Sold Out', 'event_espresso') . '</a></div>';
    }

    return $current_btn_html;

}
add_filter( 'FHEE__EE_Calendar__get_calendar_events__tooltip_reg_btn_html', 'tw_calendar_tooltip_reg_btn_html', 10, 3);