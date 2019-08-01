<?php
/*
Plugin Name: Event Espresso CSV delimiter change
Description: Allows you to set the delimiter used within the CSV export file.
Author: Tony Warwick
Version: 1.0
*/

function tw_ee_set_csv_delimiter($delimiter)
{
    // Change the CSV delimited/seperator to be a semi colon.
    $delimiter = ';';
    return $delimiter;
}
add_filter('FHEE__EE_CSV__fputcsv2__delimiter', 'tw_ee_set_csv_delimiter');
