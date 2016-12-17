<?php
/**
 * Adds a new question type of "password", which can be used when creating new questions, and is
 * rendered as a password input in forms
 * @param $question_types
 * @return mixed
 */
function ee_add_question_type_as_options( $question_types ) {
    $question_types[ 'password' ] = __( 'Password', 'event_espresso' );
    return $question_types;
}
add_filter( 'FHEE__EEM_Question__construct__allowed_question_types', 'ee_add_question_type_as_options' );

function ee_generate_question( $input, $question_type, $question_obj, $options ) {
    if( ! $input && $question_type == 'password' ) {
        //must return an EE_Form_Input_Base child object, see event-espresso-core/libraries/form_sections/inputs. If they want to create a different class it needs to extend EE_Form_Input_Base and get autoloaded via EEH_Autoloader
        return new EE_Password_Input( $options );
    }
}
add_filter( 'FHEE__EE_SPCO_Reg_Step_Attendee_Information___generate_question_input__default', 'ee_generate_question', 10, 4 );