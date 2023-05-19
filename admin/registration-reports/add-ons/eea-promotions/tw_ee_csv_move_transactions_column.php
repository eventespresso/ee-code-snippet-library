<?php
// Please do NOT include the opening php tag, except of course if you're starting with a blank file

/*
 * This function hooks into the CSV filter and moves the 'Transaction Promotions' column to be after the 'Ticket Datetimes' column.
 */
add_filter(
    'FHEE__EventEspressoBatchRequest__JobHandlers__RegistrationsReport__reg_csv_array',
    'tw_ee_csv_move_transactions_column',
    20,
    2
);
function tw_ee_csv_move_transactions_column(
    array $csv_row,
    $reg_row
) {
	// Grab the current promotions field and pull it into another array.
	$promotions['Transaction Promotions'] = isset($csv_row['Transaction Promotions']) ? $csv_row['Transaction Promotions'] : '';
	// Merge that array back into the $csv_row after the 'Ticket Datetimes' colum.
	$csv_row = EEH_Array::insert_into_array(
        $csv_row,
        $promotions,
        esc_html__('Ticket Datetimes', 'event_espresso'),
        false
    );
	// Return the $csv_row
    return $csv_row;
}
