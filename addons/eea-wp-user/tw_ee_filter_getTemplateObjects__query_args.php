<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

/* This code snippet is an example of how to filter out expired events from the results output by [ESPRESSO_MY_EVENTS]
 * This code snippet is for the EE4 User Integration add-on.
 */

add_filter(
	'FHEE__Espresso_My_Events__getTemplateObjects__query_args',
	'tw_ee_filter_getTemplateObjects__query_args',
	10,
	3
);
function tw_ee_filter_getTemplateObjects__query_args( $query_args, $template_args, $att_id) {
	if ($template_args['object_type'] === 'Event') {
		$query_args[0]['Datetime.DTT_EVT_end'] = array( '>=', EEM_Datetime::instance()->current_time_for_query('DTT_EVT_end'));	
	}
	if ($template_args['object_type'] === 'Registration') {
		$query_args[0]['Event.Datetime.DTT_EVT_end'] = array( '>=', EEM_Datetime::instance()->current_time_for_query('DTT_EVT_end'));	
	}
	return $query_args;
}