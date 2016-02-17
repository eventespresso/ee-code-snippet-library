<?php

/* 
 * Allows you to set up Front End payment methods only viewable to logged in administrators or users with custom capabilities you specifiy
 * 
 */


function tw_ee_front_end_admin_only_payment_methods($payment_methods, $transaction, $scope) {
	//Check if the user is logged in, is not within the admin and has the correct capability
	if( is_user_logged_in() && !is_admin() ) {
		
		//Check we are within SPCO
		$checkout = EE_Registry::instance()->SSN->checkout();
		if ( $checkout instanceof EE_Checkout ) {
			
			//Sanity check to check we have a valid EE_Transaction object
			if ( $transaction instanceof EE_Transaction) {
				
				//Check for a specific capability, manage_options would generally mean the user is an Administrator
				if( current_user_can( 'manage_options' ) ) {

					//Pull in a 'Invoice' payment method
					$additional_payment_methods[] = EEM_Payment_Method::instance()->get_one_of_type( 'Invoice' );
				
				}

				//This is a custom capabilities check, meaning you can assign specific users/roles to have access to specific
				//payment methods on the front end once logged in.
				if( current_user_can( 'ee_front_end_cheque' ) ) {
				
					//Pull in a 'Check' payment method.
					$additional_payment_methods[] = EEM_Payment_Method::instance()->get_one_of_type( 'Check' );
				}
					
				//d($additional_payment_methods);
				//Check we actually have an EE_Payment_Method object and add it to the payment methods.
				foreach($additional_payment_methods as $additional_payment_method) {

					if( $additional_payment_method instanceof EE_Payment_Method ) {
						//Add the payment method to the available payment methods shown within SPCO.
						$payment_methods[] = $additional_payment_method;
					}

				}
			}
		}
	}
	return $payment_methods;
}
add_filter('FHEE__EEM_Payment_Method__get_all_for_transaction__payment_methods', 'tw_ee_front_end_admin_only_payment_methods', 10, 3 );