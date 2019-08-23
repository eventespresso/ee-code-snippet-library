<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

//This function adds the Promotion "name" and Promotion "code" to Transactions search

add_filter(
    'FHEE__Transactions_Admin_Page___get_transactions_query_params',
    'jf_ee_transactions_search_add_promotions',
    10,
    4
);

function jf_ee_transactions_search_add_promotions(
    array $params,
    $request,
    $view,
    $count
) {
    if (isset($request['s'])) {
        $search_string = '%' . sanitize_text_field($request['s']) . '%';
        $params[0]['OR']['Line_Item.LIN_desc'] = array('LIKE', $search_string);
        $params[0]['OR']['Line_Item.LIN_name'] = array('LIKE', $search_string); 
    }
    return $params;
}