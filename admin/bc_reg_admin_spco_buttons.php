<?php

defined('ABSPATH') || exit;
/**
 * This code snippet adds three buttons to teh Registration Details page
 * that link out to the Registration Checkout page for that particular registration
 * The three buttons are:
 *      Full Checkout : will progress through ALL reg steps
 *      Attendee Info : a revisit for just the attendee info reg step
 *      Payment Options : a revisit for just the payment options reg step
 */
add_action(
    'AHEE__reg_admin_details_main_meta_box_reg_details__top',
    function ($REG_ID = 0)
    {
        bcRegAdminSpcoButtons($REG_ID, 'payment_options');
        bcRegAdminSpcoButtons($REG_ID, 'attendee_information');
        bcRegAdminSpcoButtons($REG_ID);
    }
);
/**
 * @param int    $REG_ID
 * @param string $step
 * @throws EE_Error
 * @throws InvalidArgumentException
 * @throws \EventEspresso\core\exceptions\InvalidDataTypeException
 * @throws \EventEspresso\core\exceptions\InvalidInterfaceException
 */
function bcRegAdminSpcoButtons($REG_ID = 0, $step = '')
{
    $registration = EEM_Registration::instance()->get_one_by_ID($REG_ID);
    if (! $registration instanceof EE_Registration) {
        return;
    }
    $query_args = array(
        'e_reg_url_link' => $registration->reg_url_link(),
    );
    switch ($step) {
        case 'attendee_information':
            $query_args['step']    = $step;
            $query_args['revisit'] = true;
            $link_label            = esc_html__(' Checkout Attendee Info');
            $icon                  = 'dashicons dashicons-clipboard';
            break;
        case 'payment_options':
            $query_args['step']    = $step;
            $query_args['revisit'] = true;
            $link_label            = esc_html__(' Checkout Payment Options');
            $icon                  = 'dashicons dashicons-money';
            break;
        default:
            $query_args['step'] = 'attendee_information';
            $link_label         = esc_html__(' Full Checkout');
            $icon               = 'dashicons dashicons-cart';
    }
    echo EEH_Template::get_button_or_link(
        add_query_arg($query_args, EE_Config::instance()->core->reg_page_url()),
        $link_label,
        'button secondary-button right',
        $icon,
        $link_label
    );
}
