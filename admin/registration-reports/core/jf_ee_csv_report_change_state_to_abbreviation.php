<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

add_filter(
    'FHEE__EventEspressoBatchRequest__JobHandlers__RegistrationsReport__reg_csv_array',
    'jf_ee_csv_report_change_state_to_abbreviation',
    10,
    2
);
function jf_ee_csv_report_change_state_to_abbreviation(
    array $csv_row,
    $reg_row
) {
    $csv_row['State'] = EEM_State::instance()->get_var(
        array(array('STA_ID' => $reg_row['Attendee_Meta.STA_ID'])),
        'STA_abbrev'
    );
    return $csv_row;
}