<?php
/**
 * Please do NOT include the opening php tag, except of course if you're starting with a blank file
 * example code below shows how to add the receipt_email param to the Stripe payment intent
 * tw_ee_stripe_receipts
 * @param array          $stripe_data the request data
 * @param EEI_Payment    $payment
 * @param EE_Transaction $transaction
 * @param array          $billing_info
 * @return               $stripe_data filtered
 * 
 */

function tw_ee_stripe_receipts( $stripe_data, $payment, $transaction, $billing_info) {
    if($transaction instanceof EE_Transaction) {
        $primary_registration = $transaction->primary_registration();
        
        if($primary_registration instanceof EE_Registration) {
            $primary_attendee = $primary_registration->attendee();

            if($primary_attendee instanceof EE_Attendee) {
                $stripe_data['receipt_email'] = $primary_attendee->email();
            }                    
        }
    }
    return $stripe_data;
}
add_filter('FHEE__EEG_Stripe_Onsite__doDirectPaymentWithPaymentIntents__payment_intent_data', 'tw_ee_stripe_receipts', 10, 4);