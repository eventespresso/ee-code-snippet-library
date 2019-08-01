<?php
// Please do NOT include the opening php tag, except of course if you're starting with a blank file

/*
 * This function adds the name and type of 'people' assigned to an event through the People Add-on.
 * The column name added to the CSV is 'People' and the format used for each person is {person_name}({person_type})
 */
add_filter('FHEE__EventEspressoBatchRequest__JobHandlers__RegistrationsReport__reg_csv_array', 'tw_ee_add_people_to_report', 10, 2);
function tw_espresso_add_people_to_report($reg_csv_array, $reg_row)
{
    // Setup the 'People' column even if emtpy.
    $reg_csv_array['People'] = '';

    // Load the people add-on helper.
    EE_Registry::instance()->load_helper('People_View');
    $people_in_types = EEH_People_View::get_people_for_event($reg_row['Registration.EVT_ID']);

    // Create an array to hold all of the peoples names.
    $peoples_names = array();
    // Loops though all people types and pull the people assigned to them.
    foreach ($people_in_types as $people_type => $people) {
        // Loop through each person assigned to a type.
        foreach ($people as $person) {
            // Check we have an EE_Person object.
            if ($person instanceof EE_Person) {
                // Add the persons name and type to the peoples_names array
                // This adds them to the CSV using the format - "{person_name}({person_type})"
                $peoples_names[] = $person->full_name() . '(' . $people_type . ')';
            }
        }
    }

    // If we have peoples name, implode the array and add it to the CSV.
    if (!empty($peoples_names)) {
        $reg_csv_array['People'] = implode(', ', $peoples_names);
    }

    return $reg_csv_array;
}
