<?php

/* 
 * Adds the header "Billing Form" to the top of all billing forms.
 * Tested with Event Espresso 4.8.32 and Authorize.net AIM gateway
 */

function ee_add_header_to_billing_forms( $form_object ) {
	if( $form_object instanceof EE_Billing_Info_Form ) {
		$form_object->add_subsections( 
			array(
				'header' => new EE_Form_Section_HTML( 
					EEH_HTML::h1( 
						__( 'Billing Form', 'event_espresso' )
					)
				)
			),
			null, 
			true 
		);
	}
}
add_action( 'AHEE__EE_Form_Section_Proper___construct_finalize__end', 'ee_add_header_to_billing_forms', 10, 1 );