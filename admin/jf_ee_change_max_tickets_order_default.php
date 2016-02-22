<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

// change default for "Maximum number of tickets allowed per order for this event" for new events
// 
add_filter( 'FHEE__caffeinated_event_registration_options__template__settings', 'jf_ee_change_max_tickets_order_default', 10, 1);

function jf_ee_change_max_tickets_order_default( $settings_array ) {
  if ( $_REQUEST['action'] == 'create_new' ) {
    $limit = 5; // set limit here
    $settings_array['max_registrants'] = '<p>
      <label for="max-registrants">' .  __('Maximum number of tickets allowed per order for this event: ', 'event_espresso') . '</label>
      <input class="ee-numeric" type="text" id="max-registrants" name="additional_limit" value="' . $limit . '" size="4" />
      </p>';
  }
  return $settings_array;
}