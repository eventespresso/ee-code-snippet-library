<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

/* The following can be added to make a second People admin list table menu item (at the main menu level) 
 * that will work with the Simple Page Ordering plugin 
 */

// modify how the People custom post type is registered so it's hierarchical **and** shown in the main WP menu 
add_filter( 'FHEE__EE_Register_CPTs__get_CPTs__cpts', 'ee_modify_visibility_of_event_people_cpt' );
function ee_modify_visibility_of_event_people_cpt( $cpt_registry_array ) {
    if ( isset( $cpt_registry_array['espresso_people'] ) ) {
        $cpt_registry_array['espresso_people']['args']['show_ui'] = true;
        $cpt_registry_array['espresso_people']['args']['show_in_menu'] = true;
        $cpt_registry_array['espresso_people']['args']['hierarchical'] = true;
        $cpt_registry_array['espresso_people']['args']['capabilities']['create_posts'] = 'do_not_allow';
    }
    return $cpt_registry_array;
}

// change the post query’s orderby parameter to menu_order so the posts display in the defined order
add_filter( 'posts_orderby', 'jf_eea_people_archive_orderby' );
function jf_eea_people_archive_orderby( $orderby ) {
    global $wpdb;
    if( is_post_type_archive( 'espresso_people' ) || 
        is_tax( 'espresso_people_categories' ||
        is_tax( 'espresso_people_type' ) ) {
        $orderby = "menu_order";
        return $orderby;
    }
    // not a people archive, return default order by
    return $orderby;
}