<?php
/*
 * If a datetime spans more than 7 days this function changes the datetime end date used in the calendar to match the
 * start date so the event shows only on the start date. 
*/

function tw_calendar_datetime_end_equal_start($calendar_datetime, $datetime) {

    //Only change the event end date to match the event start date if the event it longer then 7 days
    if ( $datetime->length('days', true) > 7 ) {
        //Grab the current start_date
        $start_date = $datetime->start_date('F j, Y');
        //Set the end date to match the start date on the calendar datetime
        $calendar_datetime->datetime()->set_end_date($start_date);
    }

    return $calendar_datetime;
}
add_filter('FHEE__EE_Calendar__get_calendar_events__calendar_datetime', 'tw_calendar_datetime_end_equal_start', 10, 2);