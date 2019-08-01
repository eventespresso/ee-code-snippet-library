<?php
// Please do NOT include the opening php tag, except of course if you're starting with a blank file

/*
 * This function is an example of to limit the registration report CSV to include only specific columns.
 */ 
add_filter('FHEE__EventEspressoBatchRequest__JobHandlers__RegistrationsReport__reg_csv_array', 'espresso_reg_report_filter_columns', 10, 2);
function espresso_reg_report_filter_columns($csv_row, $registration_db_row)
{
    $filtered_csv_row = array_intersect_key(
        $csv_row,
        array_flip(
            array(
                __('First Name', 'event_espresso'),
                __('Last Name', 'event_espresso'),
                __('Email Address', 'event_espresso'),
                'radio',// custom question's admin label, doesn't need to be translated. note though: if you ever change the custom question's admin label, this code will need to be adjusted
            )
        )
    );
    return $filtered_csv_row;
}
