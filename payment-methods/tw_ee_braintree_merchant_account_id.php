<?php
/**
 * Adds a 'Merchant Account ID' setting field to the Braintree payment method settings and then adds the value (if set) to the sale params used during the transaction.
 * Braintree add-on version 1.0.3 or higher required
 */

function tw_ee_add_braintree_merchant_account_id( $pms_form_args, $payment_method_object, $payment_method_instance) {
    //Add the Merchant Account ID field to the Braintree setting page.
    $pms_form_args['extra_meta_inputs']['merchant_account_id'] = new EE_Text_Input( 
        array(
          'html_label_text' => sprintf( __('Merchant Account ID: %1$s', 'event_espresso'), $payment_method_object->get_help_tab_link() ),
          'html_help_text' => __("If you have multiple merchant accounts in Braintree, specify a specific mechant account ID here or leave balnk for the default account.", 'event_espresso')
        )
    );
    return $pms_form_args;
}
add_filter('FHEE__EE_PMT_Braintree_Dropin__generate_new_settings_form__form_filtering', 'tw_ee_add_braintree_merchant_account_id', 10, 3);


function tw_ee_braintree_sale_params( $sale_params, $transaction ) {
    //Check we have a transaction.
    if( $transaction instanceof EE_Transaction ) {
        $last_payment_method = $transaction->payment_method();
        //Check we have a payment method object.
        if($last_payment_method instanceof EE_Payment_Method ) {
            //Pull the merchant_account_id from the payment method.
            $merchant_account_id = $last_payment_method->get_extra_meta('merchant_account_id', true); 

            //If we have a merchant_account_id and the sale_params do not already have one set, add the new value to sale_params.
            if($merchant_account_id && empty($sale_params['merchant_account_id'])) {
                $sale_params['merchant_account_id'] = $merchant_account_id;
            } 

        }
    }
    return $sale_params;
}
add_filter('FHEE__EEG_Braintree_Dropin__do_direct_payment__sale_parameters', 'tw_ee_braintree_sale_params', 10, 2);