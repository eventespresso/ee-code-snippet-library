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
    $primary_reg = $transaction->primary_registration();    
    $stripe_data['receipt_email'] = $primary_reg->attendee()->email();
    return $stripe_data;
}
add_filter( 
	'FHEE__EEG_Stripe_Onsite__do_direct_payment__stripe_data_array', 
	'tw_ee_stripe_data_array', 
	10, 
	4 
);