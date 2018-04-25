<?php
/*
 * Use this function to display only the event title on the calendar entries rather than the default 'Event Title: Datetime Name'.
*/

function tw_ee_EE_Datetime_In_Calendar__to_array_for_json__title( $original_title, $datetime_in_calendar) {
	$event = $datetime_in_calendar->event();
	return $event->name();
}
add_filter( 'FHEE__EE_Datetime_In_Calendar__to_array_for_json__title', 'tw_ee_EE_Datetime_In_Calendar__to_array_for_json__title', 10, 2 );