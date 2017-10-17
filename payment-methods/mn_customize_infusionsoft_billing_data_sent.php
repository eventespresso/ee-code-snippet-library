<?php
/**
 * Filters the billing state sent to Infusionsoft so that a 2 character state
 * code is sent, instead of the full state name as normal. Requires Infusionsoft add-on 2.1.7.rc.006
 */
add_filter(
    'FHEE__EE_PMT_Infusionsoft_Onsite___get_billing_values_from_form',
    function ($billing_values_outputted, EE_Billing_Info_Form $billing_form, $payment_method){
        if( $payment_method instanceof EE_PMT_Infusionsoft_Onsite) {
            $state_obj = EEM_State::instance()->get_one_by_ID($billing_form->get_input_value('state'));
            if($state_obj instanceof EE_State) {
                $billing_values_outputted['state'] = substr(
                    $state_obj->abbrev(),
                    0,
                    2
                );
            }
        }
        return $billing_values_outputted;
    },
    10,
    3
);