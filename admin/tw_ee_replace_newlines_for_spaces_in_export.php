<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

//This function replaces any new lines within the CSV values for a single space.
function tw_ee_replace_newlines_for_spaces_in_export($reg_csv_array, $reg_row) {
	foreach( $reg_csv_array as $key => $value ) {
		$clean_value = preg_replace("/[\r\n]+/", " ", $value);
		$reg_csv_array[$key] = $clean_value;
	}
	return $reg_csv_array;
}
add_filter( 'FHEE__EventEspressoBatchRequest__JobHandlers__RegistrationsReport__reg_csv_array', 'tw_ee_replace_newlines_for_spaces_in_export', 100, 2 );