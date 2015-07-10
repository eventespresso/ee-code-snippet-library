<?php

//only include these 4 columns in the registration CSV output
add_filter( 'FHEE__EE_Export__report_registrations__reg_csv_array', 'espresso_reg_report_filter_columns', 10, 2);

function espresso_reg_report_filter_columns( $csv_row, $registration_db_row ) {
	$filtered_csv_row = array_intersect_key(
			$csv_row,
			array_flip( array(
		__( 'First Name[ATT_fname]', 'event_espresso' ),
		__( 'Last Name[ATT_lname]', 'event_espresso' ),
		__( 'Email Address[ATT_email]', 'event_espresso' ),
		'radio',//custom question's admin label, doesn't need to be translated. note though: if you ever change the custom question's admin label, this code will need to be adjusted
				) ) );
	return $filtered_csv_row;
}