<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

/* Displays the event time on password protected events (Requires 3.2.9+ of the EE4 calendar add-on) */

function tw_ee_calendar_password_event_time_html( $event_time_html, $datetime, $event ) {

    if( post_password_required( $event->ID() ) ) {

        $time_format = EE_Registry::instance()->addons->EE_Calendar->config()->time->format;
        
        $startTime =  '<span class="event-start-time">' . $datetime->start_time($time_format) . '</span>';
        $endTime = '<span class="event-end-time">' . $datetime->end_time($time_format) . '</span>';

        $event_time_html = '<span class="time-display-block">' . $startTime;
        $event_time_html .= $endTime ? ' - ' . $endTime : '';
        $event_time_html .= '</span>';
    }
    return $event_time_html;
}
add_filter( 'FHEE__EE_Calendar__get_calendar_events__event_time_html', 'tw_ee_calendar_password_event_time_html', 10, 3 );