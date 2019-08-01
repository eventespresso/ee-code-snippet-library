<?php
// Please do NOT include the opening php tag, except of course if you're starting with a blank file

/*
 * This function allows you to set an array of 'allowed' fields that will be output to the registration CSV.
 * The order in which they are set in the 'allowed_fields_in_order' array is the order that will be used by the CSV itself.
 */
function tw_ee_espresso_reg_report_filter_columns_ordered($csv_row, $registration_db_row)
{
    // Set the allowed fields here and also set them in the order you want them to be displayed within the CSV
    $allowed_fields_in_order = array(
        __('Last Name', 'event_espresso'),
        __('First Name', 'event_espresso'),
        __('Email Address', 'event_espresso'),
    );

    // Flip the array so the values are now the keys.
    $allowed_fields_in_order = array_flip($allowed_fields_in_order);
    
    // Set the value for each of the array elements to an empty string.
    // This is incase any of the above questions do not exist in the current registration's questions,
    // they still need to be included in the row but the value should be nothing.
    $allowed_fields_in_order = array_fill_keys(array_keys($allowed_fields_in_order), '');
    
    // Sets $filtered_csv_row to only contain the 'allowed' fields.
    $filtered_csv_row = array_intersect_key(
        $csv_row,
        $allowed_fields_in_order
    );

    // Now lets set $filtered_csv_row to use the same custom order we set $allowed_fields_in_order to
    $filtered_csv_row = array_merge($allowed_fields_in_order, $filtered_csv_row);

    return $filtered_csv_row;
}
add_filter('FHEE__EventEspressoBatchRequest__JobHandlers__RegistrationsReport__reg_csv_array', 'tw_ee_espresso_reg_report_filter_columns_ordered', 10, 2);
