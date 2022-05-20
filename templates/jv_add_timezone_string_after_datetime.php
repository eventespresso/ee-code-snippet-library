<?php

// Remove the <?php tag if added to your functions.php
// It allow you to add your timezone (i.e GMT+3) after the date time event information.
// Replace the "your timezone string" by whatever you want.
add_filter( 'FHEE__espresso_list_of_event_dates__datetime_html', 'jv_add_timezone_string_after_datetime', 10, 2 );
function jv_add_timezone_string_after_datetime( $datetime_html, EE_Datetime $datetime ) {
	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );
	return '
	<span class="dashicons dashicons-calendar"></span>
	<span class="ee-event-datetimes-li-daterange">' . $datetime->date_range($date_format) . '</span>
	<br/>
	<span class="dashicons dashicons-clock"></span>
	<span class="ee-event-datetimes-li-timerange">' . $datetime->time_range($time_format) . ' your timezone string</span>
	';
}