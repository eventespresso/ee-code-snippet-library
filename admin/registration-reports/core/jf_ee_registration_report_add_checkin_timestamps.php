<?php
// Please do NOT include the opening php tag, except of course if you're starting with a blank file

/**
 * Use this filter with EE4.6+ to add another column onto the registration CSV report
 * that shows checkin timestamps for each registration.
 */

add_filter(
    'FHEE__EventEspressoBatchRequest__JobHandlers__RegistrationsReport__reg_csv_array',
    'espresso_add_checkin_timestamp_csv_report',
    10,
    2
);
function espresso_add_checkin_timestamp_csv_report(array $csv_row, $reg_db_row)
{
    $checkin_rows = (array) EEM_Checkin::instance()->get_all_wpdb_results(
        array(
            array(
                'REG_ID' => $reg_db_row['Registration.REG_ID'],
            ),
        )
    );
    $checkins_for_csv_col = array();
    $datetime_checkins_for_csv_col = array();
    foreach ($checkin_rows as $checkin_row) {
        $checkins_for_csv_col[] = $checkin_row['Checkin.CHK_timestamp'];
        $checkin_for_dtt_id = $checkin_row['Checkin.DTT_ID'];
        $checkin_for_dtt_name = \EEM_Datetime::instance()->get_var(
            array(
                array('DTT_ID' => $checkin_for_dtt_id)
            ),
            'DTT_name'
        );
        $datetime_checkins_for_csv_col[] = $checkin_for_dtt_name ?
            $checkin_for_dtt_name . ' - ID ' . $checkin_for_dtt_id :
            $checkin_for_dtt_id;
    }
    $csv_row[ (string) __('Checkin timestamps', 'event_espresso') ] = implode(' + ', $checkins_for_csv_col);
    $csv_row[ (string) __('Checked in for Datetime', 'event_espresso') ] = implode(' + ', $datetime_checkins_for_csv_col);
    return $csv_row;
}
