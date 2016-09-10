<?php
/*
Usage: Create an 'espresso' directory within your child theme.
Create a custom_iframe_css.css and place it within the above directory.
Add your custom CSS to that file which will be added to the iframe output.
This function can be placed within your child themes function.php file or a custom functions plugin.
*/

function tw_ee_ticket_selector_iframe_custom_css( $css_locations_array ) {

	$css_locations_array[] = get_stylesheet_directory_uri() . '/espresso/custom_iframe_css.css';

	return $css_locations_array;

}
add_filter('FHEE__EED_Ticket_Selector__ticket_selector_iframe__css', 'tw_ee_ticket_selector_iframe_custom_css' );