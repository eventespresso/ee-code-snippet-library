<?php
/**
 * Filters the billing state sent to Infusionsoft so that a 2 character state and country
 * code is sent, instead of the full state and country name as normal. Requires Infusionsoft add-on 2.1.7.rc.006
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
            $billing_values_outputted['country'] = substr(
                $billing_form->get_input_value('country'),
                0,
                2
            );
        }
        return $billing_values_outputted;
    },
    10,
    3
);

//require the phone number in the Infusionsoft billing form that appears in Event Espresso.
//Depending on how you setup your Bambora account, the phone number (and other fields)
//may or may not be requuired
add_action(
    'AHEE__EE_Form_Section_Proper___construct_finalize__end',
    function (EE_Form_Section_Proper $formsection, EE_Form_Section_Proper $parent_form_section = null, $name = null){
        if( $name === 'Infusionsoft_Payment_Info_Form') {
            $formsection->get_input('phone')->set_required(true);
        }
    },
    10,
    3
);