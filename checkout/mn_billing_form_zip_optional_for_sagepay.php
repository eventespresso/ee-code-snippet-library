<?php

/**
 * Makes it so zip input is optional for the sagepay payment method.
 * This could be changed to any other payment method with a billing form,
 * just find the form's exact name from the EE_PMT_*.payment_method.php file and
 * look in generate_new_billing_form method. 
 * Tested on EE 4.8.30 and Sagepay gateway 1.1.3
 * @param array $options
 * @param EE_Form_Section_Proper $form
 */
function ee_sagepay_zip_optional( $options, $form ) {
	if( $form instanceof EE_Billing_Attendee_Info_Form 
		&& isset( $options[ 'name' ] ) 
		&& $options[ 'name' ] === 'Sage_Pay_Billing_Form' 
	) {
		$zip_input = $options[ 'subsections' ][ 'zip' ];
		if( $zip_input instanceof EE_Text_Input ) {
			$zip_input->set_required( false );
		}
	}
	return $options;
}
add_filter( 'FHEE__EE_Form_Section_Proper___construct__options_array', 'ee_sagepay_zip_optional', 10, 2 );