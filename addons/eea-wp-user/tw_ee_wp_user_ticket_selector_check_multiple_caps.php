<?php
/* Please do NOT include the opening php tag, except of course if you're starting with a blank file */
/* 
 * This code snippet is for the EE4 User Integration add-on.
 * Using the filter here, you can specify set multiple capabilities in the 'Ticket Capability Requirement' option.
 * Each capability should be separated by a comma (,)
 */

function tw_ee_wp_user_ticket_selector_check_multiple_caps( $cap, $id ){
	
	// Create an array of capabilities using the string set on the ticket.
	// Separate each capability using a comma (,)
	$caps_array = explode(',', $cap);

	// Loop over the cap(s) set on the ticket and check if the current user has any of those caps.
	foreach($caps_array as $single_cap) {
		// Trim any whitespace that may be before/after the capability.
		$single_cap = trim($single_cap);
		
		//Check if the user has the cap on the account.
		if( current_user_can($single_cap)) {
			// If the user has the current cap, return it as they have access to the ticket.
			return $single_cap;
		}
	}
	// The user does not have the cap(s) on their account, just return the current caps.
	return $cap;
}
add_filter( 'FHEE__EE_Capabilities__current_user_can__cap__wp_user_ticket_selector_check', 'tw_ee_wp_user_ticket_selector_check_multiple_caps', 10, 2);