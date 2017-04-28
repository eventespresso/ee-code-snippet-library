<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

//This function allows to to set which status will be used by default when applying a payment within EE.
//Setting the value to NAN will set the dropdown to use the 'Leave the same' option.
//You can also use a 'STS' value such as "RPP" to set to Pending Payment.
function tw_ee_custom_payment_drs() {
    wp_add_inline_script( 
        'espresso_txn',
        'jQuery( document ).ready(function($) {
           $("#display-txn-admin-apply-payment").click( function() {
           		$(".txn-reg-status-change-reg-status").val("NAN");
           });
        });'
    );
}
add_action( 'admin_enqueue_scripts', 'tw_ee_custom_payment_drs', 20 );