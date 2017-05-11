<?php
/**
 * This is an example that shows how to change to the default admin event list query params.
 *
 */

add_filter( 
  'FHEE__Events_Admin_Page__get_events__query_params', 
  'jf_ee_change_event_list_default_query_params', 
  10, 
  2 
);
function jf_ee_change_event_list_default_query_params( array $params, $req_data ) {
  if ( ! array_key_exists( 'active_status', $req_data ) ) {
    $params[0]['Datetime.DTT_EVT_start'] = array( '>', current_time( 'mysql', true ) );
    $params['order_by'] = 'Datetime.DTT_EVT_start';
    $params['order'] = 'asc';
  }
  return $params;
}