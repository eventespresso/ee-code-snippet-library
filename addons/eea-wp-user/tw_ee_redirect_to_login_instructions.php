<?php
/* 
 * When a user views the a page using the [ESPRESSO_MY_EVENTS] shortcode they'll see a 'Login to see your registrations.'
 * Currently that link will always direct to {site_url}/wp-login.php
 * 
 * This filter uses the 'Registration Page URL' set in the user intergration settings if we have one, otherwise the default.
 */

function tw_ee_redirect_to_login_instructions( $original_link ) {

    //Pull the current page url.
    $redirect_url = EEH_URL::current_url();

    //Build the default login url, redirect back to current page.
    $login_url = wp_login_url($redirect_url);

    //If we have a custom 'Registration Page URL' set in the user intergration settings tab, use that url, again we set redirect_to to the current page..
    if( isset(EE_Registry::instance()->CFG->addons->user_integration->registration_page) ) {
        $login_url = add_query_arg( array( 'redirect_to' => urlencode($redirect_url)), EE_Registry::instance()->CFG->addons->user_integration->registration_page);      
    }
    return '<a class="ee-wpui-login-link" href="' . $login_url . '">' .         
        esc_html__('Login to see your registrations.', 'event_espresso') . '</a>';
}
add_filter('FHEE__Espresso_My_Events__process_shortcode__redirect_to_login_instructions', 'tw_ee_redirect_to_login_instructions');