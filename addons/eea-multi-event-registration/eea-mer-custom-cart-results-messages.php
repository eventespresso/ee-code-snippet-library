<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

add_filter(
  'FHEE__EED_Multi_Event_Registration__get_cart_results_results_message',
  'my_custom_get_cart_results_results_message',
  10,
  2
);
function my_custom_get_cart_results_results_message($text, $ticket_count) {
  $text = sprintf(
    _n(
      'Message for 1 item.', // singular
      'Message for %1$s items.', // plural
      $ticket_count,
      'event_espresso'
    ),
    $ticket_count
  );
  return $text;
}
add_filter(
  'FHEE__EED_Multi_Event_Registration__get_cart_results_current_cart_message',
  'my_custom_get_cart_results_current_cart_message',
  10,
  2
);
function my_custom_get_cart_results_current_cart_message($text, $total_tickets) {
  $text = sprintf(
    _n(
      'There is currently 1 item in your basket.', // singular
      'There are currently %1$d items in your basket.', // plural
      $total_tickets,
      'event_espresso'
     ),
    $total_tickets
  );
  return $text;
}