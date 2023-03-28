<?php
/**
 * Please do NOT include the opening php tag, except of course if you're starting with a blank file
 * example code below shows how to add the receipt_email param to the Stripe call's data
 * tw_ee_stripe_data_array
 * @param array          $stripe_data the request data
 * @param EEI_Payment    $payment
 * @param EE_Transaction $transaction
 * @param array          $billing_info
 * @return               $stripe_data filtered
 * 
 */

function tw_ee_stripe_data_array( 
    $stripe_data, 
    $payment, 
    $transaction, 
    $billing_info
) {
    if( !empty($billing_info['email']) ) {
            $stripe_data['receipt_email'] = $billing_info['email'];
    }
    return $stripe_data;
}
add_filter( 
    'FHEE__EEG_Stripe_Onsite__doDirectPaymentWithPaymentIntents__payment_intent_data', 
    'tw_ee_stripe_data_array', 
    10, 
    4 
);