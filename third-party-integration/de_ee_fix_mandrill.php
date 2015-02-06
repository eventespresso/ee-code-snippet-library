<?php 
if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/*
 * You can take this and install it as a WordPress Plugin and activate
 */
/*
  Plugin Name:		Fix wpMandrill.
  Plugin URI:  		https://gist.github.com/nerrad/2686a4be42da2ca76047
  Description: 		wpMandrill doesn't setup formatted to field addresses to work correctly with the mandrill api.  This fixes that.
  Version: 			1.0.0
  Author: 			Darren Ethier
  Author URI: 	http://roughtsmootheng.in
  License: 			GPLv2

  Copyright 			(c) 2008-2014 Event Espresso  All Rights Reserved.

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

function de_ee_fix_wpmandrill_to( $payload ) {
	$new_to = array();
	if ( isset( $payload['to'] ) ) {
		foreach ( $payload['to'] as $to )  {
			$new_to[] = de_ee_maybe_parse_formatted_email( $to );
		}
	} else {
		return $payload;
	}

	$payload['to'] = $new_to;
	return $payload;
}
add_filter( 'mandrill_payload', 'de_ee_fix_wpmandrill_to' );


/**
 * This takes an incoming email address from mandrill and if this is a formatted to address we break it into the component
 * parts.
 *
 * @see wp_mail() the native wp_mail() function has this code.
 *
 * @param array $to  process to address by wpMandrill plugin
 *
 * @return array new processed array.
 */
function de_ee_maybe_parse_formatted_email( $to ) {
	$to_address = isset( $to['email'] ) ? $to['email'] : null;

	if ( empty( $to_address ) ) return $to; //get out because wpMandrill has likely started doing something strange.

	if( preg_match( '/(.*)<(.+)>/', $to_address, $matches ) ) {
		if ( count( $matches ) == 3 ) {
			$to['name'] = $matches[1];
			$to['email'] = $matches[2];
		}
	}

	return $to;
}
