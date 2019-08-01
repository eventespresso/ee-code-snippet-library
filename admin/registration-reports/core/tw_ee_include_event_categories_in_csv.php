<?php
// Please do NOT include the opening php tag, except of course if you're starting with a blank file

/*
 * This function includes the event categories set on each event in the registration reports.
 */
function tw_ee_include_event_categories_in_csv($reg_csv_array, $reg_row)
{
    $EVT_ID = $reg_row['Event_CPT.ID'];
    $terms = array();
    $event_categories = get_the_terms($EVT_ID, 'espresso_event_categories');
    if ($event_categories) {
        foreach ($event_categories as $term) {
            $terms[] = $term->name;
        }
        $terms = implode(', ', $terms);
    }
    $reg_csv_array['Event Categories'] = !empty($terms) ? $terms : null;
    return $reg_csv_array;
}
add_filter('FHEE__EventEspressoBatchRequest__JobHandlers__RegistrationsReport__reg_csv_array', 'tw_ee_include_event_categories_in_csv', 10, 2);
