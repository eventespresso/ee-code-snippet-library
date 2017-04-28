<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

// change default for "Send Related Messages" to Yes on when changing the registration status
function tw_ee_reg_status_change_form_send_notifications_default( $options, $form ) {
	if( $form instanceof EE_Form_Section_Proper 
		&& isset( $options[ 'name' ] ) 
		&& $options[ 'name' ] === 'reg_status_change_form' 
	) {
		$send_notifications_input = $options[ 'subsections' ][ 'send_notifications' ];
		if( $send_notifications_input instanceof EE_Yes_No_Input ) {
			$send_notifications_input->set_default( TRUE );
		}
	}
	return $options;
}
add_filter( 'FHEE__EE_Form_Section_Proper___construct__options_array', 'tw_ee_reg_status_change_form_send_notifications_default', 10, 2 );