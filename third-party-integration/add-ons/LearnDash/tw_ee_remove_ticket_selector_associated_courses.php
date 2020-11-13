<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

//This function removes the action used to add the 'Associated Courses' section in the ticket selector ticket details section.
add_action( 'wp_head', 'tw_ee_remove_ticket_selector_associated_courses' );
function tw_ee_remove_ticket_selector_associated_courses() {
	$LearnDashEspresso = LearnDash_Event_Espresso::init();
	remove_action( 'AHEE__ticket_selector_chart_template__after_ticket_date', array($LearnDashEspresso, 'show_course_on_ticket') );
}