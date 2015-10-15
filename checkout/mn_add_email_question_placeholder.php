<?php

/* 
 * Modifies the "email" question's input so it adds placeholder text.
 * Works for 4.8.16+
 */

add_filter( 'FHEE__EE_SPCO_Reg_Step_Attendee_Information___generate_question_input__input_constructor_args', 'add_placeholder_text', 10, 4);

function add_placeholder_text( $form_construction_args, $reg, $question, $answer ) {
	if( $question instanceof EE_Question &&
			$question->system_ID() == 'email' ) {
		$form_construction_args[ 'html_other_attributes' ] =  
			'placeholder="email@domain.org"';
	}
	return $form_construction_args;
}
