<?php
/*
 * This function adds a 'Make a Frontend payment' button to EE view transaction page in the admin.
 * See: https://monosnap.com/file/WAHwMBE8NndtAHra5t0wxBfT9tOi6n
 */

add_filter('FHEE__Transactions_Admin_Page__getActionButtons__actions', 'tw_ee_add_additional_transactions_actions', 2, 10);
function tw_ee_add_additional_transactions_actions($actions, $transaction)
{
    // Pull the primary registrant from the transaction.
    $registration = $transaction->primary_registration();
    // Check we have a EE_Registration object, the transaction is not already complete and monies can be applied.
    if ($registration instanceof EE_Registration
        && $transaction->status_ID() !== EEM_Transaction::complete_status_code
        && $registration->owes_monies_and_can_pay()
    ) {
        // Add a 'Make a Frontend payment' button to the current actions.
        $actions['frontend_payment'] = EEH_Template::get_button_or_link(
            $registration->payment_overview_url(true),
            esc_html__('Make Payment from the Frontend.', 'event_espresso'),
            'button secondary-button',
            'dashicons dashicons-money'
        );
    }
    // Returns actions.
    return $actions;
}
