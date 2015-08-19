<?php
/**
 * Use this filter with EE4.6-4.7 to add another column onto the registration CSV report
 * that shows the price modifiers for each registration's ticket purchased.
 * This does not apply to 4.8+ which means it will not include promotions and transaction-wide surcharges/discounts.
 */

add_filter( 'FHEE__EE_Export__report_registrations__reg_csv_array', 'espresso_add_ticket_base_price', 10, 2);
function espresso_add_ticket_base_price( $reg_csv_array, $reg_row ) {

	//want the line item that's a child of this registration's ticket's line item
	$line_item_id_for_reg = EEM_Line_Item::instance()->get_var(
			array(
				array(
					'Ticket.TKT_ID' => $reg_row['Registration.TKT_ID']
				)
			));
	$sub_line_items = EEM_Line_Item::instance()->get_all(
			array(
				array(
					'LIN_parent' => $line_item_id_for_reg,
					'LIN_type' => EEM_Line_Item::type_sub_line_item
				),
				'order_by' => array( 'LIN_order' => 'asc' )
			));

	$sub_line_item_details = array();
	foreach( $sub_line_items as $sub_line_item ) {
		$sub_line_item_details[] = sprintf( __( '%1$s %2$s', 'event_espresso' ), $sub_line_item->name(), $sub_line_item->get_pretty( 'LIN_total', 'localized_float' ) );
	}

	$reg_csv_array[ __( 'Ticket Price Breakdown', 'event_espresso' ) ] = implode('+', $sub_line_item_details );
	return $reg_csv_array;
}
