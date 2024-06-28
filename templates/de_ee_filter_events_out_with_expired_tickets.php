<?php

/**
 * returns true if the current query is for an espresso event
 *
 * @param WP_Query $wp_query
 * @return bool
 */
function isEspressoEventsArchive(WP_Query $wp_query): bool
{
    return
        isset($wp_query->query_vars['post_type'])
        && (
            $wp_query->query_vars['post_type'] == 'espresso_events'
            || (
                is_array($wp_query->query_vars['post_type'])
                && in_array(
                    'espresso_events',
                    $wp_query->query_vars['post_type']
                )
            )
        )
        && ! $wp_query->is_singular;
}


/**
 * The purpose of this snippet is to filter the event archive (and event taxonomy archive) pages so that they exclude
 * events that have tickets no longer on sale.
 *
 *  NOTE: This query is only valid for Event Espresso 4.8+
 *
 * To Implement this code, add it to the bottom of your themes functions.php file, or add it to a site specific plugin.
 *
 * @param string   $SQL
 * @param WP_Query $wp_query
 * @return string
 */
function de_ee_tweak_event_list_exclude_ticket_expired_events_where(string $SQL, WP_Query $wp_query): string
{
    if (isEspressoEventsArchive($wp_query)) {
        $SQL .= ' AND Ticket.TKT_end_date > "' . current_time('mysql', true) . '" AND Ticket.TKT_deleted=0';
    }
    return $SQL;
}
add_filter(
    'posts_where',
    'de_ee_tweak_event_list_exclude_ticket_expired_events_where',
    15,
    2
);


/**
 * @param string $SQL
 * @param WP_Query $wp_query
 * @return string
 * @throws EE_Error
 * @throws ReflectionException
 */
function de_ee_tweak_event_list_exclude_ticket_expired_events_join(string $SQL, WP_Query $wp_query): string
{
    
    if (isEspressoEventsArchive($wp_query)) {
        $dates_table = EEM_Datetime::instance()->table();
        $dates_table_pk = EEM_Datetime::instance()->primary_key_name();
        $events_table = EEM_Event::instance()->table();
        $events_table_pk = EEM_Event::instance()->primary_key_name();
        $date_tickets_table = EEM_Datetime_Ticket::instance()->table();
        $tickets_table = EEM_Ticket::instance()->table();
        $tickets_table_pk = EEM_Ticket::instance()->primary_key_name();
        if (
            strpos($SQL, $dates_table) === false
            && ! $wp_query->is_espresso_event_archive
            && ! $wp_query->is_espresso_event_taxonomy
        ) {
            $SQL .= " INNER JOIN $dates_table";
            $SQL .= " ON ( $events_table.ID = $dates_table.$events_table_pk  ) ";
        }
        if (
            strpos($SQL, $date_tickets_table) === false
        ) {
            $SQL .= " INNER JOIN $date_tickets_table AS Datetime_Ticket";
            $SQL .= " ON ( Datetime_Ticket.DTT_ID = $dates_table.$dates_table_pk )";
        }
        if (
            strpos($SQL, $tickets_table) === false
        ) {
            $SQL .= " INNER JOIN $tickets_table AS Ticket";
            $SQL .= " ON ( Datetime_Ticket.TKT_ID=Ticket.$tickets_table_pk )";
        }
    }
    return $SQL;
}
add_filter(
    'posts_join',
    'de_ee_tweak_event_list_exclude_ticket_expired_events_join',
    3,
    2
);
