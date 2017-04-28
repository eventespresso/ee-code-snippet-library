<?php
/**
 * Some CSV-reading programs interpret phone numbers as large numbers, and so show them using scientific notation.
 * This prevents that by putting phone numbers in quotes for both the registration report and attendee report.
 * Tested with EE 4.9.38.p
 */
function mn_use_quotes_around_phone_numbers_in_reg_reports($csv_row, $db_row)
{
    $attendee_phone_field = EEM_Attendee::instance()->field_settings_for('ATT_phone');
    $attendee_phone_column_name =\EEH_Export::get_column_name_for_field($attendee_phone_field);
    $phone_num = $csv_row[$attendee_phone_column_name];
    $csv_row[$attendee_phone_column_name] = empty($phone_num) ? '' : '"' . $phone_num . '"';
    return $csv_row;
}
add_filter(
    'FHEE__EE_Export__report_registrations__reg_csv_array',
    'mn_use_quotes_around_phone_numbers_in_reg_reports',
    10,
    2
);

function mn_use_quotes_around_phone_numbers_in_attendee_reports($csv_row, $db_row)
{
    $attendee_phone_field = EEM_Attendee::instance()->field_settings_for('ATT_phone');
    $attendee_phone_column_name = $attendee_phone_field->get_nicename();
    $phone_num = $csv_row[$attendee_phone_column_name];
    $csv_row[$attendee_phone_column_name] = empty($phone_num) ? '' : '"' . $phone_num . '"';
    return $csv_row;
}
add_filter(
    'FHEE___EventEspresso_core_libraries_batch_JobHandlers_AttendeesReport__get_csv_data__row',
    'mn_use_quotes_around_phone_numbers_in_attendee_reports',
    10,
    2
);