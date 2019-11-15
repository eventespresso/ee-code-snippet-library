<?php

/*
 * This function can be used to populate empty additional registrant's questions using the answers proivded byt the primary registrant in the Registration CSV report.
 * For example if you collect 'Parent Information' on the primary registrant only and then only child names for additional registrants,
 * this snippet uses the Primary registrants parent info answers additional registration in the group  on the CSV.
 */

add_filter('FHEE__EventEspressoBatchRequest__JobHandlers__RegistrationsReport__reg_csv_array', 'tw_ee_populate_additional_registrants_questions_using_primary_reg', 20, 2);
function tw_ee_populate_additional_registrants_questions_using_primary_reg($reg_csv_array, $reg_row)
{
    if ($reg_row['Registration.REG_group_size'] > 1 && $reg_row['Registration.REG_count'] !== 1) {
        // Pull the primary registrant object.
        $primary_registration = EEM_Registration::instance()->get_one(
            array(
                array(
                    'TXN_ID'    => $reg_row['TransactionTable.TXN_ID'],
                    'REG_count' => 1,
                ),
            )
        );

        // Pull the primary registrant asnwers.
        $answers = \EEM_Answer::instance()->get_all_wpdb_results(array(
            array('REG_ID' => $primary_registration->ID()),
            'force_join' => array('Question'),
        ));
        // Now fill out the questions the primary registrant answers but the registrant has not.
        foreach ($answers as $answer_row) {
            if ($answer_row['Question.QST_ID']) {
                $question_label = \EEH_Export::prepare_value_from_db_for_display(
                    \EEM_Question::instance(),
                    'QST_admin_label',
                    $answer_row['Question.QST_admin_label']
                );
            } else {
                $question_label = sprintf(__('Question $s', 'event_espresso'), $answer_row['Answer.QST_ID']);
            }
            // If a value has already been set in the CSV, leave it alone.
            if (!empty($reg_csv_array[ $question_label ])) {
                continue;
            }
            if (isset($answer_row['Question.QST_type'])
                && $answer_row['Question.QST_type'] == \EEM_Question::QST_type_state
            ) {
                $reg_csv_array[ $question_label ] = \EEM_State::instance()->get_state_name_by_ID(
                    $answer_row['Answer.ANS_value']
                );
            } else {
                // This isn't for html, so don't show html entities
                $reg_csv_array[ $question_label ] = html_entity_decode(
                    \EEH_Export::prepare_value_from_db_for_display(
                        \EEM_Answer::instance(),
                        'ANS_value',
                        $answer_row['Answer.ANS_value']
                    )
                );
            }
        }

        // Pull the primary attendee
        $primary_attendee = $primary_registration->attendee();
        
        // Which primary attendee fields are including:
        $att_fields_to_include = array(
            'ATT_address',
            'ATT_address2',
            'ATT_city',
            'STA_ID',
            'CNT_ISO',
            'ATT_zip',
            'ATT_phone',
        );

        // add attendee columns
        foreach ($att_fields_to_include as $att_field_name) {
            $field_obj = EEM_Attendee::instance()->field_settings_for($att_field_name);
            $column_name = EEH_Export::get_column_name_for_field($field_obj);
            if (empty($reg_csv_array[ $column_name ])) {
                if ($att_field_name == 'STA_ID') {
                    $value = $primary_attendee->state_name();
                } elseif ($att_field_name == 'CNT_ISO') {
                    $value = $primary_attendee->country_name();
                } else {
                    $value = EEH_Export::prepare_value_from_db_for_display(
                        EEM_Attendee::instance(),
                        $att_field_name,
                        $primary_attendee->get($att_field_name)
                    );
                }
                $reg_csv_array[ $column_name ] = $value;
            }
        }
    }
    return $reg_csv_array;
}
