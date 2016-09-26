<?php

function tw_ee_espresso_reg_report_filter_columns_ordered( $csv_row, $registration_db_row ) {

	//Set the allowed fields here and also set them in the order you want them to be displayed within the CSV
	$allowed_fields_in_order = array(
		__( 'Last Name[ATT_lname]', 'event_espresso' ),
		__( 'First Name[ATT_fname]', 'event_espresso' ),
		__( 'Email Address[ATT_email]', 'event_espresso' ),
	);

	//Sets $filtered_csv_row to only contain the 'allowed' fields.
	$filtered_csv_row = array_intersect_key(
			$csv_row,
			array_flip( $allowed_fields_in_order ) 
		);
	
	//Now lets set $filtered_csv_row to use the same custom order we set $allowed_fields_in_order to
	$filtered_csv_row = array_merge(array_flip($allowed_fields_in_order), $filtered_csv_row );

	return $filtered_csv_row;
}
add_filter( 'FHEE__EE_Export__report_registrations__reg_csv_array', 'tw_ee_espresso_reg_report_filter_columns_ordered', 10, 2);