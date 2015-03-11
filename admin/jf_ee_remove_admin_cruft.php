<?php
/**
 * You can use these functions remove the admin footer text, 
 * the about page menu item, 
 * and the extensions/services menu item from the WP admin.
 */

// remove admin menu item: About
// remove admin menu item: Extensions and Services
add_action( 'admin_menu', 'jf_ee_remove_menu_cruft', 11 );
function jf_ee_remove_menu_cruft() {
    remove_submenu_page( 'espresso_events', 'espresso_about' );
    remove_submenu_page( 'espresso_events', 'espresso_packages' );    
}

// remove "powered by Event Espresso" admin footer text
add_filter( 'admin_footer_text', 'jf_ee_remove_admin_footer_text', 11 ); 
function jf_ee_remove_admin_footer_text() {
    remove_filter( 'admin_footer_text', array( 'EE_Admin', 'espresso_admin_footer' ), 10 );
}