<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

// removes sold events from displaying in the Upcoming Events widget

add_filter(
	'FHEE__EEW_Upcoming_Events__widget__where',
	'jf_ee_remove_sold_out_events_from_widget', 
	10, 
	3
);
function jf_ee_remove_sold_out_events_from_widget($where, $category, $show_expired){
    $where['status'] = array('IN', array('publish'));
    return $where;
}