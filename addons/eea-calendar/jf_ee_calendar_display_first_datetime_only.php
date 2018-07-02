<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

add_filter( 
    'FHEE__EED_Espresso_Calendar__get_calendar_events__query_params', 
    'ee_calendar_display_first_datetime_only', 
    10, 
    7 
);
function ee_calendar_display_first_datetime_only( 
    $query_params, 
    $category_id_or_slug,
    $venue_id_or_slug,
    $public_event_stati,
    $start_date,
    $end_date,
    $show_expired 
) {
    $query_params[0]['DTT_order'] = 1;
    return $query_params;
}