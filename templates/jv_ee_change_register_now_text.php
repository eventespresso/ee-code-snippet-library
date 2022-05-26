<?php
// Remove the <?php tag if added to your functions.php
// It changes the Register Now button string (Located on the Event page)
add_filter( 'FHEE__EE_Ticket_Selector__display_ticket_selector_submit__btn_text', 'jv_ee_change_register_now_text' );
function jv_ee_change_register_now_text() {

	return 'Purchase now'; // Replace by your text
	
}