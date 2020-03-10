<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

//This is an example of how you can change the register now button within the calendar using str_replace.
//This function changes 'Register Now' to 'Custom Text'.
function tw_ee_calendar_tooltip_reg_now_btn_str_replace($tooltip_reg_btn_html, $event, $datetime) {

	$tooltip_reg_btn_html = str_replace('Register Now', 'Custom Text', $tooltip_reg_btn_html);

	return $tooltip_reg_btn_html;
}
add_filter('FHEE__EE_Calendar__get_calendar_events__tooltip_reg_btn_html', 'tw_ee_calendar_tooltip_reg_now_btn_str_replace', 10, 3);
