<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

add_filter(
    'FHEE__Registrations_Admin_Page___get_where_conditions_for_registrations_query', 
    'my_custom_search_field_reg', 
    10, 
    2
);
function my_custom_search_field_reg( $where, $request ) {
    if (isset($request['s'])) {
        $search_string = '%' . sanitize_text_field($request['s']) . '%';
        $where['OR*search_conditions']['Answer.ANS_value'] = array('LIKE', $search_string);
    }
    return $where;
}