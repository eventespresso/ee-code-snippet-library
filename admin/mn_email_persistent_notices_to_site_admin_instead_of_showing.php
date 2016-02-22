<?php

/* 
 * Makes it so persistent notices aren't shown in the admin, but are instead emailed
 * to the site admin.
 */

add_filter( 'FHEE__EE_Error__get_persistent_admin_notices', 'email_me_instead_of_persistent_notices', 10, 4 );

function email_me_instead_of_persistent_notices( $notices, $option_name, $return_url ) {
	
	$site_url = get_option( 'siteurl' );
	//get the main site's admin email
	if( is_multisite() ) {
		switch_to_blog( 1 );
	}
	wp_mail( get_option( 'admin_email' ), $site_url . ' admin notices', EEH_Template::layout_array_as_table( $notices ) );
	if( is_multisite() ) {
		restore_current_blog();
	}
	//now empty out the notices because we've addressed them
	update_option( $option_name, array() );
	return array();
}