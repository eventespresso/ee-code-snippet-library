<?php
/**
*
* Tested with EE4 User Integration Add-on v1.1.4 
*
* Removes the user integration settings from the event editor based on the users capabilities.
* The default is the 'manage_options' capability which all Administrators will usually have, changing this
* allows control of which users have access to this settings meta box.
*
*
**/

function tw_ee_remove_wp_user_meta_on_cap() {

	if ( ! current_user_can( 'manage_options' ) ) {
	 	remove_meta_box( 'eea_wp_user_integration', 'espresso_events', 'side' );
	}
		
}
add_action( 'do_meta_boxes', 'tw_ee_remove_wp_user_meta_on_cap' );
