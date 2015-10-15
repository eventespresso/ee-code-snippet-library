<?php

/* 
 * Adds a new admin-only payment method. You'll need to deactivate and reactivate Event Espresso for this to work, with
 * Event Espresso 4.8.15 or higher
 */


add_filter( 'FHEE__EEH_Activation__add_default_admin_only_payments__default_admin_only_payment_methods', 'add_manual_labour_hours' );

function add_manual_labour_hours( $admin_only_payment_methods ) {
	$admin_only_payment_methods[__( 'Manual Labour', 'event_espresso' )] = __( 'Manual Labour Hours', 'event_espresso' );
	return $admin_only_payment_methods;
}