<?php
/*
* Usage of FHEE__EEH_Template__format_currency__amount filter to return free instead of 0.00 for free tickets
*/
function de_ee_convert_zero_to_free( $amount, $return_raw ) {
	// we don't want to mess with requests for unformated values because those may get used in calculations
	return $return_raw || $amount >= 0 ? $amount : __('free', 'event_espresso');
}
add_filter( 'FHEE__EEH_Template__format_currency__amount', 'de_ee_convert_zero_to_free', 10, 2 );
