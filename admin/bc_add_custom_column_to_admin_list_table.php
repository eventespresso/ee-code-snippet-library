<?php
defined( 'ABSPATH' ) || exit;

// not good to use globals, just doing this for demonstration purposes
// please feel free to hard code these values where you can and remove the globals
global $espresso_admin_page;
global $custom_column_name;
// the URL for every espresso admin page will contain a URL parameter named "page"
// set the following variable equal to that URL parameter
$espresso_admin_page = 'espresso_registrations';
$custom_column_name = 'myCustomColumnName';
// now add the hooks
add_filter(
	"FHEE_manage_event-espresso_page_{$espresso_admin_page}_columns",
	"bc_filter_registration_list_table_columns", 10, 2
);
add_action(
	"AHEE__EE_Admin_List_Table__column_{$custom_column_name}__event-espresso_page_{$espresso_admin_page}",
	"bc_registration_list_table_{$custom_column_name}", 10, 2
);

/**
 * this function adds the column name to the array of table headers
 *
 * @param array $columns
 * @param string $screen
 * @return array
 */
function bc_filter_registration_list_table_columns( $columns, $screen ) {
	global $espresso_admin_page;
	global $custom_column_name;
	if ( $screen === "{$espresso_admin_page}_default" ) {
		$columns = EEH_Array::insert_into_array(
			$columns,
			array( $custom_column_name => 'Attendee Phone #' ),
			'ATT_fname',
			false
		);
	}
	return $columns;
}

/**
 * this function echoes out the data you want to appear in your custom column.
 * PLZ change "myCustomColumnName" in the function name to match the value of $custom_column_name
 *
 * @param \EE_Registration $item
 * @param string           $screen
 */
function bc_registration_list_table_myCustomColumnName( $item, $screen ) {
	global $espresso_admin_page;
	if ( $screen === "{$espresso_admin_page}_default" && $item instanceof EE_Registration ) {
		$attendee = $item->attendee();
		$phone = $attendee->phone();
		echo ! empty( $phone ) ? $phone : '# not in service';
	}
}







// End of file bc_add_custom_column_to_admin_list_table.php
// Location: /ee-code-snippet-library/admin/bc_add_custom_column_to_admin_list_table.php