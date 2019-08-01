<?php
/*
Plugin Name: Exclude custom fields from EE registrations export.
Description: Allows you to set felds that will be excluded from the registrations CSV export file.
Author: Tony Warwick
Version: 1.0
*/

function ee_exclude_custom_fields_from_export($reg_csv_array, $reg_row)
{
    $fields_to_exclude_from_csv = array(
        // Add the fields you wish to exclude from the CSV here.
        // These excluded fields are an example of how to remove fields, your list will be different.
        __('Payment Date(s)', 'event_espresso'),
        __('Payment Method(s)', 'event_espresso'),
        __('Gateway Transaction ID(s)', 'event_espresso'),
        __('Check-Ins', 'event_espresso')
    );

    foreach ($fields_to_exclude_from_csv as $single_field_to_exclude) {
        // For each field within $fields_to_exclude_from_csv, check if that value is within the CSV array.
        if (array_key_exists($single_field_to_exclude, $reg_csv_array)) {
            // If the field is set remove it from the array.
            unset($reg_csv_array[ $single_field_to_exclude ]);
        }
    }
    return $reg_csv_array;
}
add_filter('FHEE__EventEspressoBatchRequest__JobHandlers__RegistrationsReport__reg_csv_array', 'ee_exclude_custom_fields_from_export', 100, 2);
