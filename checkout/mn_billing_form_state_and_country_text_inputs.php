<?php

/* 
 * Changes the state and country dropdowns to text inputs, and corrects how they get autofilled.
 */


add_filter( 'FHEE__EE_Billing_Attendee_Info_Form__state_field', 'billing_locale_text_field', 10, 1 );
add_filter( 'FHEE__EE_Billing_Attendee_Info_Form__country_field', 'billing_locale_text_field', 10, 1 );
function billing_locale_text_field( $original_field ) {
    return new EE_Text_Input( array( 'required' => false, 'html_class' => 'ee-billing-qstn'));
}
add_filter( 'FHEE__EE_Billing_Attendee_Info_Form__populate_from_attendee', 'billing_autofill_differently', 10, 2 );
function billing_autofill_differently( $autofill_data, $attendee ) {
	if( $attendee instanceof EE_Attendee) {
		if( $attendee->state_ID() ) {
			$autofill_data[ 'state' ] = $attendee->state_name();
		} else { 
			$autofill_data[ 'state' ] = '';
		}
		if( $attendee->country_ID() ) {
			$autofill_data[ 'country' ] = $attendee->country_name();
		} else { 
			$autofill_data[ 'country' ] = '';
		}
	}
	return $autofill_data;
}
