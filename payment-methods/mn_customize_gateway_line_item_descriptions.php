<?php
/**
 * This code snippet customizes the line items as they are sent to gateways like
 * - Authorize.net AIM
 * - PayPal Pro
 * - PayPal Express
 * - Mijireh
 * Any other gateways that receive an itemized list of purchases and use GatewayDataFormatter.
 * This specific snippet changes the line item's description by adding all the contact/attendee names onto the end,
 * as well as the registration(s) code(s).
 * If you want to instead change the line item's NAME, instead use the filter `FHEE__EE_gateway___line_item_name`.
 * Tested with Event Espresso 4.9.54.p
 */
use EventEspresso\core\services\payment_methods\gateways\GatewayDataFormatter;
function mn_customize_line_item_desc_to_include_attendee_names($original_line_item_name, GatewayDataFormatter $formatter, EE_Line_Item $line_item, EE_Payment $payment)
{
    //find all the contact names associated with this line item. ie, all attendees for registrations for the line item's
    //ticket on the same transaction
    $attendees = EEM_Attendee::instance()->get_all(
        array(
            array(
                'Registration.TKT_ID' => $line_item->get('OBJ_ID'),
                'Registration.TXN_ID' => $line_item->get('TXN_ID')
            )

        )
    );
    //now let's add their name and reg codes to the item's description
    $attendee_names = array();
    foreach ($attendees as $attendee) {
        if($attendee instanceof EE_Attendee) {
            $regs_for_this_ticket = $attendee->get_registrations(
                array(
                    array(
                        'TKT_ID' => $line_item->get('OBJ_ID')
                    )
                )
            );
            $reg_codes = array();
            foreach ($regs_for_this_ticket as $registration) {
                if ($registration instanceof EE_Registration) {
                    $reg_codes[] = $registration->reg_code();
                }
            }
            $attendee_names[] = sprintf(
                esc_html__('%1$s (%2$s)', 'event_espresso'),
                $attendee->full_name(),
                implode(',', $reg_codes)
            );
        }
    }
    return sprintf(
        esc_html__('%1$s %2$s', 'event_espresso'),
        $original_line_item_name,
        implode(',', $attendee_names)
    );

}
add_filter('FHEE__EE_Gateway___line_item_desc','mn_customize_line_item_desc_to_include_attendee_names',1, 4);
