<?php
/**
 * PLEASE READ AND FOLLOW ALL INSTRUCTIONS IN CAPS
 *
 * THIS SNIPPET CURRENTLY REQUIRES CORE BRANCH FET-6593-txn-surcharge
 *
 * IN ORDER FOR THIS TO WORK YOU NEED TO ADD A CUSTOM QUESTION
 * BY LOGGING INTO THE WORDPRESS 	ADMIN AND GOING TO :
 *        Event Espresso > Registration Form
 * AND THEN CLICKING ON "Add New Question"
 * FOR THIS EXAMPLE CODE I CREATED A QUESTION NAMED "Ticket Printing"
 * SET IT'S TYPE TO "Dropdown" AND GAVE IT THE FOLLOWING TWO OPTIONS:
 * 		"you print tickets at home"
 * 		"we print and ship tickets"
 * THEN SET THE QUESTION TO REQUIRED
 *
 * BECAUSE THIS QUESTION SHOULD ONLY BE ASKED ONCE PER TRANSACTION
 * I ALSO CREATED A QUESTION GROUP CALLED "Ticket Fees"
 * AND ADDED THE "Ticket Printing" QUESTION TO THAT GROUP
 *
 * THEN ON MY EVENT ( Event Espresso > Events > Edit Event ),
 * I CHECKED OFF THE  "Ticket Fees" QUESTION GROUP
 * IN THE "Questions for Primary Registrant" SIDEBAR METABOX
 *
 * THIS WAY, ONLY THE PRIMARY REGISTRANT WILL BE ASKED
 * TO SELECT A TICKET PRINTING OPTION, WHICH WILL THEN
 * CONTROL WHICH CHARGE IS ADDED TO THE TRANSACTION
 *
 * PLZ SEE ADDITIONAL INSTRUCTIONS IN FUNCTIONS BELOW
 *
 * bc_ee_determine_whether_to_apply_surcharge
 *
 * @return void
 */
function bc_ee_determine_whether_to_apply_surcharge() {
	// CHANGE $surcharge_QST_ID VALUE TO MATCH THE ID OF YOUR QUESTION
	$surcharge_QST_ID = 13;
	if ( isset( $_REQUEST[ 'ee_reg_qstn' ] ) ) {
		foreach ( $_REQUEST[ 'ee_reg_qstn' ] as $registrations ) {
			if ( ! empty( $registrations ) ) {
				foreach ( $registrations as $QST_ID => $response ) {
					if ( $QST_ID == $surcharge_QST_ID ) {
						switch ( $response ) {
							// CHANGE THESE TO MATCH THE ANSWER OPTIONS FOR YOUR QUESTION
							// THEN EDIT / ADD / DELETE THE FUNCTIONS BELOW
							// WHOSE NAMES MATCH THESE OPTIONS
							// YOU CAN ADD NEW OPTIONS, JUST MAKE SURE TO HAVE A CORRESPONDING
							// FUNCTION FOR SETTING THE SURCHARGE DETAILS
							case 'you print tickets at home' :
								// apply the surcharge
								add_filter( 'FHEE__bc_ee_apply_transaction_surcharge__apply_surcharge', '__return_true' );
								// hook into function below to set surcharge details
								add_filter( 'FHEE__bc_ee_apply_transaction_surcharge__surcharge_details', 'bc_ee_print_at_home_fee_surcharge_details' );
								break;
							case 'we print and ship tickets' :
								// apply the surcharge
								add_filter( 'FHEE__bc_ee_apply_transaction_surcharge__apply_surcharge', '__return_true' );
								// hook into function below to set surcharge details
								add_filter( 'FHEE__bc_ee_apply_transaction_surcharge__surcharge_details', 'bc_ee_ticket_shipping_fee_surcharge_details' );
								break;

						}
					}
				}
			}
		}
	}
}
add_action( 'AHEE__EE_System__core_loaded_and_ready', 'bc_ee_determine_whether_to_apply_surcharge', 1 );



/**
 * EDIT THIS TO HOLD THE DETAILS FOR ONE OF YOUR ANSWER OPTIONS
 * @return array
 */
function bc_ee_print_at_home_fee_surcharge_details() {
	return array(
		'name'        		=> 'printing fee',
		'code'        		=> 'print-at-home-fee',
		'description' 	=> 'postal fee for shipping tickets',
		'unit_price'  	=> 1.00,
		'taxable'     		=> false,
	);
}



/**
 * EDIT THIS TO HOLD THE DETAILS FOR ONE OF YOUR ANSWER OPTIONS
 * @return array
 */
function bc_ee_ticket_shipping_fee_surcharge_details() {
	return array(
		'name'        		=> 'shipping fee',
		'code'        		=> 'ticket-shipping-fee',
		'description' 	=> 'postal fee for shipping tickets',
		'unit_price'  	=> 2.50,
		'taxable'     		=> false,
	);
}



/**
 * DO NOT EDIT ANYTHING EXCEPT DEFAULT SURCHARGE DETAILS
 *
 * bc_ee_apply_transaction_surcharge
 *
 * @param \EE_Checkout $checkout
 * @return \EE_Checkout
 */
function bc_ee_apply_transaction_surcharge( EE_Checkout $checkout ) {
	// DEFAULT SURCHARGE DETAILS - EDIT THIS
	$surcharge_details = apply_filters(
		'FHEE__bc_ee_apply_transaction_surcharge__surcharge_details',
		array(
			//  name for surcharge that will be displayed, ie: 'printing fee'
			'name'        => 'shipping fee',
			// unique code used to identify surcharge in the db, ie: 'print-at-home-fee'
			'code'        => 'ticket-shipping-fee',
			// 'fee for printing tickets'
			'description' => 'postal fee for shipping tickets',
			// surcharge amount
			'unit_price'  => 2.50,
			// whether or not tax is applied on top of the surcharge
			'taxable'     => false,
		)
	);
	// STOP EDITING
	// apply the surcharge ?
	if ( ! apply_filters( 'FHEE__bc_ee_apply_transaction_surcharge__apply_surcharge', false ) ) {
		return $checkout;
	}
	// verify checkout
	if ( ! $checkout instanceof EE_Checkout ) {
		return $checkout;
	}
	// verify cart
	$cart = $checkout->cart;
	if ( ! $cart instanceof EE_Cart ) {
		return $checkout;
	}
	// verify grand total line item
	$grand_total = $cart->get_grand_total();
	if ( ! $grand_total instanceof EE_Line_Item ) {
		return $checkout;
	}
	// has surcharge already been applied ?
	$existing_surcharge = $grand_total->get_child_line_item( $surcharge_details[ 'code' ] );
	if ( $existing_surcharge instanceof EE_Line_Item ) {
		return $checkout;
	}
	EE_Registry::instance()->load_helper( 'Line_Item' );
	$ticket_printing_subtotal = EE_Line_Item::new_instance( array(
		'LIN_code' => 'ticket-printing-fees',
		'LIN_name' => __( 'Ticket Printing Fees', 'event_espresso' ),
		'LIN_type' => EEM_Line_Item::type_sub_total,
		'TXN_ID'   => $checkout->transaction->ID()
	) );
	$grand_total->add_child_line_item( $ticket_printing_subtotal );
	$ticket_printing_subtotal->add_child_line_item(
		EE_Line_Item::new_instance( array(
			'LIN_name'       => $surcharge_details[ 'name' ],
			'LIN_desc'       => $surcharge_details[ 'description' ],
			'LIN_unit_price' => floatval( $surcharge_details[ 'unit_price' ] ),
			'LIN_quantity'   => 1,
			'LIN_is_taxable' => $surcharge_details[ 'taxable' ],
			'LIN_order'      => 0,
			'LIN_total'      => floatval( $surcharge_details[ 'unit_price' ] ),
			'LIN_type'       => EEM_Line_Item::type_line_item,
			'LIN_code'       => $surcharge_details[ 'code' ],
		) )
	);
	return $checkout;
}
add_filter( 'FHEE__EED_Single_Page_Checkout___initialize_checkout__checkout', 'bc_ee_apply_transaction_surcharge' );









// End of file bc_ee_apply_transaction_surcharge.php