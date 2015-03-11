<?php
/**
 * This is an alternative filter for adding events to posts.
 *
 * 1. Adds events to ANY query for posts (including core wp functions like wp_recent_posts() that suppress filters ).
 * 2. Ensures we don't accidentally add posts (and events) into custom queries for other post types. (eg sliders).
 */

function de_ee_add_espresso_events_to_posts( $WP_Query ) {
	//do not do this in the admin queries
	if ( is_admin() ) {
		return;
	}
	if ( $WP_Query instanceof WP_Query && ( $WP_Query->is_feed || $WP_Query->is_posts_page  || ( $WP_Query->is_home && ! $WP_Query->is_page ) ||  ( isset( $WP_Query->query_vars['post_type'] ) && ( $WP_Query->query_vars['post_type'] == 'post' || is_array( $WP_Query->query_vars['post_type'] ) && in_array( 'post', $WP_Query->query_vars['post_type'] ) ) ) ) ) {
		//if post_types ARE present and 'post' is not in that array, then get out!
		if ( isset( $WP_Query->query_vars['post_type'] ) && $post_types = (array) $WP_Query->query_vars['post_type'] ) {
			if ( ! in_array( 'post', $post_types ) ) {
				return;
			}
		} else {
			$post_types = array( 'post' );
		}

		if ( ! in_array( 'espresso_events', $post_types )) {
			$post_types[] = 'espresso_events';
			$WP_Query->set( 'post_type', $post_types );
		}
		return;
	}
}
add_action( 'pre_get_posts', 'de_ee_add_espresso_events_to_posts', 10 );
