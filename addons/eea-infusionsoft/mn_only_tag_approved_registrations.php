<?php

/* 
 * This code snippet is for the EE4 Infusionsoft addon.
 * Using this, contacts in Infusionsoft can only be tagged when their corresponding
 * registration in Event Espresso is approved. 
 * Ie, Infusionsoft contacts created from Event Espresso registrations who didn't finish
 * registering won't ever be tagged in Infusionsoft
 */

add_filter( 'FHEE__EEE_Infusionsoft_Registration__sync_to_infusionsoft__infusionsoft_tags', 'espresso_only_tag_approved_regs', 10, 2 );
function espresso_only_tag_approved_regs( $tags, $registration ) {
	if( 
			$registration instanceof EE_Registration && 
			! in_array( 
					$registration->status_ID(),
					array( 
						EEM_Registration::status_id_approved 
					))) {
		//reg isn't approved, don't tag it yet
		$tags = array();
	}
	return $tags;
}