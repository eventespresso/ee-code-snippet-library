<?php

function jf_ee_remove_payment_step() {
	$reg_steps = EE_Registry::instance()->CFG->registration->reg_steps;
	if ( empty( $reg_steps )) {
		$reg_steps = array(
			10 => array(
				'file_path' => SPCO_INC_PATH,
				'class_name' => 'EE_SPCO_Reg_Step_Attendee_Information',
				'slug' => 'attendee_information',
				'has_hooks' => FALSE
			),
			20 => array(
				'file_path' => SPCO_INC_PATH,
				'class_name' => 'EE_SPCO_Reg_Step_Registration_Confirmation',
				'slug' => 'registration_confirmation',
				'has_hooks' => FALSE
			),
			999 => array(
				'file_path' => SPCO_INC_PATH,
				'class_name' => 'EE_SPCO_Reg_Step_Finalize_Registration',
				'slug' => 'finalize_registration',
				'has_hooks' => FALSE
			)
		);
	}
	return $reg_steps;
}

add_filter ( 'AHEE__SPCO__load_reg_steps__reg_steps_to_load', 'jf_ee_remove_payment_step' );