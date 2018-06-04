<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

// re-order people Archive so it's ASC by last name
add_filter( 'posts_orderby', 'jf_eea_people_archive_orderby' );
function jf_eea_people_archive_orderby( $orderby ) {
    global $wpdb;
    if( is_post_type_archive( 'espresso_people' ) || is_tax( 'espresso_people_categories' )) {
        $orderby = "eam.ATT_lname ASC";
        return $orderby;
    }
    // not a people archive, return default order by
    return $orderby;
}

// add the attendee meta table to the people archive join
add_filter( 'posts_join_paged', 'jf_eea_people_archive_join_paged' );
function jf_eea_people_archive_join_paged( $join_paged_statement ) {
    global $wpdb;
    if( is_post_type_archive( 'espresso_people' ) || is_tax( 'espresso_people_categories' )) {
        $join_paged_statement .= "JOIN {$wpdb->prefix}esp_attendee_meta eam 
        ON ( eam.ATT_ID = {$wpdb->prefix}posts.ID ) ";
        return $join_paged_statement; 
    }
    // not a people archive, return default join
    return $join_paged_statement;   
}