<?php
/**
 * The purpose of this snippet is to add a noindex, follow meta tag to expired event posts 
 * so search engine indexing is blocked.
 *
 * You can implement this code by adding it to the bottom of your themes functions.php file, or add it to a site specific plugin.
 *
 */
function jf_ee_add_no_robots_tag_expired_events() {
  if ( 'espresso_events' == get_post_type() && is_single() ){
    $id = get_the_id();
    $event = EEH_Event_View::get_event( $id );
    $status = $event instanceof EE_Event ? $event->get_active_status() : '';
    if ( $status == 'DTE' ) {
        wp_no_robots();
    }
  }
}
add_action( 'wp_head', 'jf_ee_add_no_robots_tag_expired_events', 1 );