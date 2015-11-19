<?php

/**
 * Uses the saved answer of the attendee for the input's default value
 */
add_filter('FHEE__EE_SPCO_Reg_Step_Attendee_Information___generate_question_input__input_constructor_args', 'my_question_input', 10, 4);
function my_question_input( $input_args, EE_Registration $registration = null, EE_Question $question = null, EE_Answer $answer = null ) {
	if( class_exists( 'EED_WP_Users_SPCO' ) ) {
		global $current_user;
		$attendee = EED_WP_Users_SPCO::get_attendee_for_user( $current_user );
	} else {
		$attendee = null;
	}
	if( $question instanceof EE_Question &&
			! $question->system_ID() &&
			$registration instanceof EE_Registration &&
			$registration->is_primary_registrant() && 
			$attendee instanceof EE_Attendee ) {
		$input_args[ 'default' ] = $attendee->get_post_meta( 'answer_to_' . $question->ID(), true );
	}
	return $input_args;
}

//saves all the custom question answers to the attendee
add_action('AHEE__EE_Single_Page_Checkout__process_attendee_information__end', 'process_wpuser_for_attendee', 20, 2);
function process_wpuser_for_attendee( EE_SPCO_Reg_Step_Attendee_Information $spco, $valid_data) {
	if ( $spco->checkout instanceof EE_Checkout && $spco->checkout->transaction instanceof EE_Transaction ) {
		$registrations = $spco->checkout->transaction->registrations( $spco->checkout->reg_cache_where_params, true );
	}
	foreach( $registrations as $registration ) {
		foreach( $registration->answers() as $answer_obj ) {
			$registration->attendee()->update_post_meta( 'answer_to_' . $answer_obj->question_ID(), $answer_obj->value() );
		}
	}
}

