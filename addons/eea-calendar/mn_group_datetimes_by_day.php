<?php
//works with calendar 3.2.6.rc.002 or higher. Affects the month view of the calendar
//so that if there are multiple datetimes for a single event on the same day, only one
//will appear in the calendar. Week and day views are unaffected
//see https://events.codebasehq.com/projects/event-espresso/tickets/8864
//*** requires a server running PHP 5.5 or higher ***
add_filter( 'FHEE__EED_Espresso_Calendar__get_calendar_events__query_params', 'ee_calendar_group_by_day', 10, 7 );
function ee_calendar_group_by_day( $query_params, 
        $category_id_or_slug,
        $venue_id_or_slug,
        $public_event_stati,
        $start_date,
        $end_date,
        $show_expired ) {
    //only override month view
    if( ( $end_date->getTimestamp() - $start_date->getTimestamp() ) <= WEEK_IN_SECONDS ) {
        return $query_params;
    }
    //ok so it's month view. Let's issue a query grouped by event Id and date
    $query_params[ 'group_by' ] = array( 'EVT_ID', 'event_date' );
    $datetime_ids = EEM_Datetime::instance()->get_all_wpdb_results( 
            $query_params, 
            ARRAY_A, 
            array( 
                'DTT_ID' => array( 'Datetime.DTT_ID', '%d' ),
                'EVT_ID' => array( 'Datetime.EVT_ID', '%d' ),
                'event_date' => array( 'DATE( Datetime.DTT_EVT_start )', '%s' ) ) );
    $ids_only = array_column( $datetime_ids, 'DTT_ID' );
    //...and return query params so we only look for those specific datetimes
    return array(
        array( 
            'DTT_ID' => array( 'IN', $ids_only ) ),
            'limit' => count( $ids_only ) );
}