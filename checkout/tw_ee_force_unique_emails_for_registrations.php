<?php

/* 
 * This functions checks the email field of incoming registrations for duplicates, if the email address is used in multiple registrations it
 * prevents the registrations from processing and displays a notice on SPCO.
 * 
 */

function tw_ee_force_unique_emails_for_registrations(
	    $stop_processing,
        $att_nmbr,
        EE_Registration $spco_registration,
        $registrations,
        $valid_data,
        EE_SPCO_Reg_Step_Attendee_Information $spco
) {
    if ($att_nmbr !== 0 || $stop_processing) {
        //get out because we've already either verified things or another plugin is halting things.
        return $stop_processing;
    }
    $email_addresses = array();
    $user_notice     = '';
    $field_input_error = array();
    // we need to loop through each valid_data[$registration->reg_url_link()] set of data
    // to see if the is a duplicate email address used.
    // If there is then halt the presses!
    foreach ($registrations as $registration) {
        //if not a valid $reg then we'll just ignore and let spco handle it
        if (! $registration instanceof EE_Registration) {
            return $stop_processing;
        }
        $reg_url_link = $registration->reg_url_link();
        if (isset($valid_data[ $reg_url_link ]) && is_array($valid_data[ $reg_url_link ])) {
            foreach ($valid_data[ $reg_url_link ] as $form_section => $form_inputs) {
                if (! is_array($form_inputs)) {
                    continue;
                }

                //Check we have an email input.
                if (
                    ! empty($form_inputs['email'])
                ) {
                	//If any email addresses have already been added to the $email_addresses array
                	//check that the current email does not match any of those addresses
                    if(! empty($email_addresses) ) {
                    	if( in_array(strtolower($form_inputs['email']), $email_addresses) ) {
                    		//$email_addresses already contains the current registrations email address so this is a dupe.
                    		//Add a notice to SPCO and stop processing.

                            //== Change the text below to a more suitable error mesage if prreferrec ==
                    		$user_notice = '<p>Each registration must have a unique email address</p>';
                    		$stop_processing = true;
                    	}
                    }
                    //Add the email address to the array using lower case
					$email_addresses[] = strtolower($form_inputs['email']);
                }
            }
        }
    }
    //If stop_processing, add a notice to SPCO.
    if ($stop_processing) {
        EE_Error::add_error($user_notice, __FILE__, __FUNCTION__, __LINE__);
    }
	return $stop_processing;
}

add_filter('FHEE__EE_SPCO_Reg_Step_Attendee_Information___process_registrations__pre_registration_process', 'tw_ee_force_unique_emails_for_registrations', 10, 6);