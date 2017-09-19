//changes the first line item's unit price to 10, from whatever it was before
//this will be displayed during SPCO's payment options step

add_action('AHEE__Single_Page_Checkout__after_attendee_information__process_reg_step', 'custom_hook', 10, 1);
function custom_hook(EE_SPCO_Reg_Step_Attendee_Information $data)
{
    $purchases = $data->checkout->transaction->items_purchased();
    $first_item = reset($purchases);
    //we need to edit the sub-item's unit price, as
    //the line item's total is calculated from its sub-items
    $children = $first_item->children();
    $child_line_item = reset($children);
    $child_line_item->set('LIN_unit_price', 10);
    $data->checkout->cart->get_grand_total()->recalculate_total_including_taxes();
    $data->update_checkout();
}
