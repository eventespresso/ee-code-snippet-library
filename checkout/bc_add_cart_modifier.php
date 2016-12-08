<?php
/**
 * bc_add_cart_modifier
 *
 * @param \EE_SPCO_Reg_Step $payment_options_reg_step
 * @throws \EE_Error
 */
function bc_add_cart_modifier( EE_SPCO_Reg_Step $payment_options_reg_step ) {
	// CHANGE THESE TO YOUR LIKING
	$cart_modifier_name = 'my cart % surcharge';
	$cart_modifier_amount = 10.00;
	$cart_modifier_description = '10% surcharge for choosing invoice payment method';
	$cart_modifier_taxable = true; // or false if surcharge is not taxable
	$payment_methods_with_surcharges = array( 'invoice' );
	// get what the user selected for payment method
	$selected_method_of_payment = $payment_options_reg_step->checkout->selected_method_of_payment;
	if ( ! in_array( $selected_method_of_payment, $payment_methods_with_surcharges, true ) ) {
		// does not require surcharge
		return;
	}
	$cart = $payment_options_reg_step->checkout->cart;
	if ( ! $cart instanceof EE_Cart ) {
		// ERROR
		return;
	}
	$total_line_item = $cart->get_grand_total();
	if ( ! $total_line_item instanceof EE_Line_Item && ! $total_line_item->is_total() ) {
		// ERROR
		return;
	}
    $transaction = $payment_options_reg_step->checkout->transaction;
    if ( ! $transaction instanceof EE_Transaction) {
        // ERROR
        return;
    }
    //delete existing in case page is refreshed or something
    EEM_Line_Item::instance()->delete(
        array(
            array(
                'TXN_ID'   => $transaction->ID(),
                'LIN_name' => $cart_modifier_name,
            )
        )
    );
    $surcharge_line_item_id = EEH_Line_Item::add_percentage_based_item(
        $total_line_item,
        $cart_modifier_name,
        $cart_modifier_amount,
        $cart_modifier_description,
        $cart_modifier_taxable
    );
    if ($surcharge_line_item_id) {
        $surcharge_line_item = EEM_Line_Item::instance()->get_one_by_ID($surcharge_line_item_id);
        if ($surcharge_line_item instanceof EE_Line_Item) {
            // add surcharge amount to amount owing
            $payment_options_reg_step->checkout->amount_owing += $surcharge_line_item->total();
            $new_total = $total_line_item->total();
            $transaction->set_total($new_total);
            $success = $transaction->save();
            if ($success) {
                /** @type EE_Registration_Processor $registration_processor */
                $registration_processor = EE_Registry::instance()->load_class('Registration_Processor');
                $registration_processor->update_registration_final_prices($transaction);
            }
        }
    }
}
add_action( 'AHEE__Single_Page_Checkout__before_payment_options__process_reg_step', 'bc_add_cart_modifier', 10, 1 );









// End of file bc_add_cart_modifier.php
// Location: /bc_add_cart_modifier.php
