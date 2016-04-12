<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

//This function allows Yoast SEO to load within Event Espresso when you edit or create any event or venue.
function ee_yoast_seo_always_register_metaboxes_in_admin(){
	global $pagenow;
	$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
	$action = isset( $_GET['action'] ) ? $_GET['action'] : '';

	if ( $pagenow == 'admin.php' && ( $page == 'espresso_events' || $page == 'espresso_venues' )  && ( $action == 'edit' || $action == 'create_new' ) ) {
		return true;
	}
	return false;
}
add_filter( 'wpseo_always_register_metaboxes_on_admin', 'ee_yoast_seo_always_register_metaboxes_in_admin' );