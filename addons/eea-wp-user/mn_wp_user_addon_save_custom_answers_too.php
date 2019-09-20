<?php
/**
 * Code snippets that extends the Event Espresso WP User integration addon,
 * so that we also remember the user's last answer to custom questions too.
 */
add_filter('FHEE__EE_SPCO_Reg_Step_Attendee_Information___generate_question_input__input_constructor_args', 'my_question_input', 10, 4);
function my_question_input( $input_args, EE_Registration $registration = null, EE_Question $question = null, EE_Answer $answer = null ) {
	if( class_exists( 'EED_WP_Users_SPCO' ) ) {
        if(is_admin()){
            return $input_args;
        }
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
		$prev_answer_value = EEM_Answer::instance()->get_var( 
				array(
					array(
						'Registration.ATT_ID' => $attendee->ID(),
						'QST_ID' => $question->ID()
					),
					'order_by' => array(
						'ANS_ID' => 'DESC'
					),
					'limit' => 1
				),
				'ANS_value' );
		if( $prev_answer_value ) {
			$field_obj = EEM_Answer::instance()->field_settings_for( 'ANS_value' );
			$prev_answer_value = $field_obj->prepare_for_get( $field_obj->prepare_for_set_from_db( $prev_answer_value ) );
			$input_args[ 'default' ] = $prev_answer_value;
		}	
	}
	return $input_args;
}

