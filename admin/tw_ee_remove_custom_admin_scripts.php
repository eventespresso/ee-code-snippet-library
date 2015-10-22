<?php 
if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/*
 * You can take this and install it as a WordPress Plugin and activate
 */
/*
  Plugin Name:		EE remove custom scripts within admin pages
  Description: 		Use this plugin to remove custom scripts from Event Espresso admin pages
  Version: 			1.0.0
  Author: 			Tony Warwick
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

function my_admin_enqueue($hook_suffix) {
	//Event Espresso -> Events
	if($hook_suffix == 'toplevel_page_espresso_events') {
		/* Remove scripts from the Events section (including individual event editor pages) here */
	}

	//Event Espresso -> Registrations
	if($hook_suffix == 'event-espresso_page_espresso_registrations') {
		/* Remove scripts from the admin Registrations section (including individual Registration pages) here */
	}

	//Event Espresso -> Transactions 
	if($hook_suffix == 'event-espresso_page_espresso_transactions') {
		/* Remove scripts from the admin Transactions section (including individual Transaction pages) here */
	}


}
add_action( 'admin_enqueue_scripts', 'my_admin_enqueue', 100 );

//This function displays an admin notice containing the current hook_suffix for any admin page
//simply uncomment (remove the //) from the beginning of the add_action line below to use it.
function ee_display_admin_pagehook(){
	global $hook_suffix;
	if( !current_user_can( 'manage_options') ) {
		return;
	}
	?>
	<div class="error"><p>Current hook_suffix: <?php echo $hook_suffix; ?></p></div>
	<?php 
}
//add_action( 'admin_notices', 'ee_display_admin_pagehook' );