<?php

/* 
 * Allows you to set up filter payment methods based on the answer to a registration form question.
 * In this example the values of the question can be 'Bank Transfer' to only display the bank payment method
 * or 'Online Credit Card Payment' to display the Stripe payment method. Obviously change these to suit 
 * and so the case matches your registration question answers.
 */

add_filter( 'FHEE__EEM_Payment_Method__get_all_for_transaction__payment_methods', 'tw_ee_filter_payment_methods_from_reg_answer', 10, 3 );
function tw_ee_filter_payment_methods_from_reg_answer($payment_methods, $transaction, $scope) {
	$primary_reg = $transaction->primary_registration();
	if ($primary_reg instanceof EE_Registration){
		// Set the ID of the question used on the reg for here
		$question_id = 112; 

		$selected_payment_method = $primary_reg->answer_value_to_question($question_id);
		switch($selected_payment_method) {
			case 'Bank Transfer':
				$bank_payment_method = EEM_Payment_Method::instance()->get_one_of_type( 'Bank' );
				if( $bank_payment_method instanceof EE_Payment_Method) {
				    return array($bank_payment_method);
				}
			case 'Online Credit Card Payment':
				$bank_payment_method = EEM_Payment_Method::instance()->get_one_of_type( 'Stripe_Onsite' );
				if( $bank_payment_method instanceof EE_Payment_Method) {
				    return array($bank_payment_method);
				}
			default:
				return $payment_methods;
		}
	}
	return $payment_methods;
}