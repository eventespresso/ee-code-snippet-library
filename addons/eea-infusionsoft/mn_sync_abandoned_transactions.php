<?php
/**
 * Normally Infusionsoft integration only syncs when the cart is either fully paid, or the user indicated they'll pay via
 * an offline payment method (so after the payment options step).
 * This code snippet instructs the code to create an Infusionsoft order from the Event Espresso transaction a soon as the
 * the user ARRIVES at the payment options step, not after.
 * Tested with Infusionsoft add-on 2.2.0.rc.007
 * and Event Espresso 4.9.58.rc.005
 */

add_filter('FHEE__EEE_Infusionsoft_Transaction__sync_to_infusionsoft__transaction_stati_to_sync_to_IS','mn_infusionsoft_sync_asap');
function mn_infusionsoft_sync_asap($stati_to_sync)
{
    $stati_to_sync[] = EEM_Transaction::abandoned_status_code;
    return $stati_to_sync;
}
