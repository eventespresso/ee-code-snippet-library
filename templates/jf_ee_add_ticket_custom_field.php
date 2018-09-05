<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

add_action(
    'AHEE__event_tickets_datetime_ticket_row_template_after_desc',
    'jf_ee_insert_my_additional_field_to_ee_tickets',
    11,
    2
);

function jf_ee_insert_my_additional_field_to_ee_tickets($tkt_row, $TKT_ID) {
    echo jf_ee_additional_field_to_ee_tickets($tkt_row, $TKT_ID)->get_html_and_js();
}

add_action(
    'AHEE__espresso_events_Pricing_Hooks___update_tkts_new_ticket',
    'jf_ee_update_capability_on_ticket',
    10,
    4
);

add_action(
    'AHEE__espresso_events_Pricing_Hooks___update_tkts_update_ticket',
    'jf_ee_update_capability_on_ticket',
    10,
    4
);

function jf_ee_additional_field_to_ee_tickets($tkt_row, $TKT_ID) {
    $ticket = EE_Registry::instance()->load_model('Ticket')->get_one_by_ID($TKT_ID);
    $info = $ticket instanceof EE_Ticket ? $ticket->get_extra_meta('ee_ticket_extra_info', true, '') : '';
    EE_Registry::instance()->load_helper('HTML');       
    return new EE_Form_Section_Proper(
        array(
            'name'            => 'extra-ee-ticket-info-container-' . $tkt_row,
            'html_class'      => 'extra-ee-ticket-info-container',
            'layout_strategy' => new EE_Div_Per_Section_Layout(),
            'subsections'     => 
            array(
                'TKT_extra_info'                  => new EE_Text_Area_Input(
                    array(
                        'html_class'              => 'tkt-extra-info ee-full-textarea-inp',
                        'html_name'               => 'extra-ee-ticket-info_input['
                                                     . $tkt_row
                                                     . '][TKT_extra_info]',
                        'html_label_text'         => esc_html__(
                            'Extra Ticket Information:',
                            'event_espresso'
                        ),
                        'default'                 => $info,
                        'display_html_label_text' => false
                    )
                )
            ) // end EE_Form_Section_Proper subsections
        ) // end  main EE_Form_Section_Proper options array
    ); // end EE_Form_Section_Proper
}

function jf_ee_update_capability_on_ticket(
    EE_Ticket $tkt, 
    $tkt_row, 
    $tkt_form_data, 
    $all_form_data
) {
    try {
        $ticket_id = $tkt_form_data instanceof EE_Ticket ? $tkt_form_data->ID() : $tkt->ID();
        $form      = jf_ee_additional_field_to_ee_tickets($tkt_row, $ticket_id);
        if ($form->was_submitted()) {
            $form->receive_form_submission();
            if ($form->is_valid()) {
                $valid_data = $form->valid_data();
                $tkt->update_extra_meta('ee_ticket_extra_info', $valid_data['TKT_extra_info']);
            }
        }
    } catch (EE_Error $e) {
        $e->get_error();
    }
}


// display in TS additional info section
add_action(
    'AHEE__ticket_selector_chart_template__ticket_details__after_description',
    'my_display_extra_ticket_info',
    10,
    3
);
function my_display_extra_ticket_info(
    $ticket,
    $ticket_price,
    $display_ticket_price
) {
    $info = $ticket instanceof EE_Ticket ? $ticket->get_extra_meta('ee_ticket_extra_info', true, '') : '';
    echo do_shortcode($info);
}