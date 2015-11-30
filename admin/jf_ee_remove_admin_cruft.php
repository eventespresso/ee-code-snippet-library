<?php
/**
 * You can use some or all of these functions to remove the admin footer text,
 * the Event Espresso news widget,
 * the redirect to the Welcome screen after an update,
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

// stop redirect to about page after update
add_action( 'init', 'jf_ee_remove_about_ee_redirect', 4 );
function jf_ee_remove_about_ee_redirect() {
    if ( class_exists( 'EE_System' )){
        $system = EE_System::instance();
        remove_action( 'AHEE__EE_System__load_CPTs_and_session__start', array( $system, 'redirect_to_about_ee' ), 9 );  
    }
}

// remove New @ Event Espresso admin widget
function jf_ee_remove_news_metabox(){
    $current_screen = get_current_screen();
    remove_meta_box( 'espresso_news_post_box', $current_screen->base, 'side' );
}
add_action( 'admin_head', 'jf_ee_remove_news_metabox' );

// remove "powered by Event Espresso" admin footer text
add_filter( 'admin_footer_text', 'jf_ee_remove_admin_footer_text', 11 ); 
function jf_ee_remove_admin_footer_text() {
    remove_filter( 'admin_footer_text', array( 'EE_Admin', 'espresso_admin_footer' ), 10 );
}