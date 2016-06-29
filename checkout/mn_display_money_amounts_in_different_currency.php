<?php

/* 
 * Uses filters added in EE4.9.2 to show all dollar amounts on the frontend in French Francs
 * instead of the site's default currency (amounts sent to gateways are UNaffected, and the admin
 * still shows all money amounts in the site's default currency).
 * Theoretically, you could modify these callbacks to choose the currency based on some event meta
 * (although this would probably be hard to use with the Multi Event Registration addon, because that
 * can show multiple events in the checkout at the same time); and you could use a 3rd party
 * currency conversion service to find the actual exchange rate.
 */

add_filter( 'FHEE__EEH_Template__format_currency__raw_amount', 'convert_to_euros_amount', 10, 2 );
function convert_to_euros_amount( $amount ){
    if( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX )  ) {
        $amount *= 2;
    }
    return $amount;
}
add_filter( 'FHEE__EEH_Template__format_currency__CNT_ISO', 'convert_to_other_country_currency', 10, 1 );
function convert_to_other_country_currency( $CNT_ISO ){
    if( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        $CNT_ISO = 'FR';
    }
    return $CNT_ISO;
}