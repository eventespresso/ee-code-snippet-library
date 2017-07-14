<?php


/**
 * PLEASE READ AND FOLLOW ALL INSTRUCTIONS IN CAPS
 *
 * IN ORDER FOR THIS TO WORK YOU NEED TO ADD A CUSTOM QUESTION
 * BY LOGGING INTO THE WORDPRESS ADMIN AND GOING TO :
 *        Event Espresso > Registration Form
 * AND THEN CLICKING ON "Add New Question"
 *
 * FOR THIS EXAMPLE CODE I CREATED TWO NEW QUESTIONS:
 *
 *      ONE NAMED "T-shirt Size"
 *      SET ITS TYPE TO "Dropdown" AND GAVE IT THE FOLLOWING OPTIONS:
 *            "small"
 *            "medium"
 *            "large"
 *      (ANSWER VALUES ARE CASE SENSITIVE)
 *
 *      THEN CLICKED "Save and Close"
 *
 *      AND A SECOND QUESTION NAMED "T-shirt Quantity"
 *      AND SET ITS TYPE TO "Number"
 *
 *      THEN CLICKED "Save and Close"
 *
 * ADDITIONAL QUESTIONS COULD BE ADDED IN A SIMILAR FASHION.
 *
 * I ALSO CREATED A QUESTION GROUP CALLED "Products"
 * AND ADDED THE "T-shirt Size" AND "T-shirt Quantity" QUESTIONS TO THAT GROUP
 *
 * !!! IMPORTANT !!!
 * YOUR QUANTITY QUESTION MUST APPEAR >> AFTER << THE OPTION QUESTION
 * WHEN ORDERING THE QUESTIONS IN THE QUESTION GROUP
 *
 * THEN ON MY EVENT ( Event Espresso > Events > Edit Event ),
 * I CHECKED OFF THE  "Products" QUESTION GROUP
 * IN THE "Questions for Primary Registrant" SIDEBAR METABOX
 * AS WELL AS THE "Questions for Additional Registrants" SIDEBAR METABOX
 *
 * THIS WAY, ALL REGISTRANTS WILL BE ASKED BOTH QUESTIONS,
 * WHICH WILL THEN CONTROL THE EXTRA CHARGES ADDED TO THE TRANSACTION.
 *
 * PLZ FOLLOW ALL ADDITIONAL INSTRUCTIONS BELOW THAT ARE WRITTEN IN CAPS
 */


/**
 * EDIT THIS ARRAY TO HOLD THE DETAILS FOR YOUR PRODUCT OPTIONS.
 * THIS CAN HANDLE ANY NUMBER OF ITEMS AS LONG AS THE CORRESPONDING QUESTION IDs ARE CORRECT
 * AND THE PRODUCT OPTION DETAIL KEYS MATCH THE QUESTION VALUES
 */
$products = array(
    0 => array(
        // CHANGE NUMBER VALUE TO MATCH THE ID OF YOUR PRODUCT QUESTION
        'product_question_id'     => 11,
        // CHANGE NUMBER VALUE TO MATCH THE ID OF YOUR QUANTITY QUESTION
        'product_qty_question_id' => 12,
        // AN ARRAY THAT HOLDS DETAILS FOR EACH PRODUCT OPTION YOU ADDED AS QUESTION OPTIONS
        'product_option_details'  => array(
            // KEYS FOR THIS ARRAY SHOULD MATCH THE VALUES YOU ENTERED FOR YOUR QUESTION OPTION VALUES
            'small'  => array(
                // THE REST OF THESE VALUES WILL BE USED FOR GENERATING LINE ITEMS
                'name'        => 'Small T-shirt',
                'code'        => 'small-t-shirt',
                // THE REGISTRANTS NAME WILL GET PREPENDED TO THE PRODUCT DESCRIPTION WHEN DISPLAYED IN LINE ITEMS
                // ie: Small T-shirt: for Derek Zoolander. What is this? A T-shirt for ants?"
                'description' => 'What is this? A T-shirt for ants?',
                'unit_price'  => 21.00,
                'taxable'     => true,
            ),
            'medium' => array(
                'name'        => 'Medium T-shirt',
                'code'        => 'medium-t-shirt',
                'description' => 'A T-shirt for "normal" people!', // make Dr Evil air quotes whilst saying this
                'unit_price'  => 23.00,
                'taxable'     => true,
            ),
            'large'  => array(
                'name'        => 'Large T-shirt',
                'code'        => 'large-t-shirt',
                'description' => 'A T-shirt / tent',
                'unit_price'  => 25.00,
                'taxable'     => true,
            ),
            // ADD ADDITIONAL PRODUCT OPTIONS HERE
        ),
    ),
    // ADD ADDITIONAL PRODUCTS HERE
);

// THE FOLLOWING CAN BE COMMENTED OUT AND THEN ADDED ANYWHERE IN YOUR SYSTEM CODE (ASSUMING THIS FILE IS LOADED)
// AS LONG AS IT IS CALLED >> BEFORE << THE WORDPRESS "pre_get_posts" HOOK AT PRIORITY 10
// THIS MEANS THAT THE ARRAY OF PRODUCTS CAN BE GENERATED DYNAMICALLY USING PRODUCT DATA FROM ANOTHER CART.
// TO UPDATE THE SYSTEM AFTER A PRODUCT IS SOLD, YOU CAN HOOK INTO THE FOLLOWING FILTER:
//      "AHEE__bc_ee_add_product_surcharge__add_product__product_added"
// WHICH IS FOUND IN THE `bc_ee_add_product_surcharge::add_product()` METHOD
new bc_ee_add_product_surcharge($products);



/*
* !!! STOP EDITING !!!
* DON'T GO ANY FURTHER UNLESS YOU ARE REALLY CONFIDENT THAT YOU KNOW WHAT YOU ARE DOING.
*/


/**
 * bc_ee_add_product_transaction_surcharge
 *
 * @package               Event Espresso
 * @subpackage            EE Code Snippets Library
 * @author                Brent Christensen
 */
class bc_ee_add_product_surcharge
{



    /**
     * @var array $products
     */
    private $products;

    /**
     * @var EE_Checkout $checkout
     */
    private $checkout;

    /**
     * @var EE_Line_Item $grand_total
     */
    private $grand_total;

    /**
     * @var EE_Line_Item $pre_tax_subtotal
     */
    private $pre_tax_subtotal;



    /**
     * DO NOT EDIT!
     *
     * @param array $products
     */
    public function __construct($products = array())
    {
        $this->products = $products;
        add_filter(
            'FHEE__Single_Page_Checkout___check_form_submission__request_params',
            array($this, 'check_for_products')
        );
        add_action(
            'AHEE__Single_Page_Checkout__after_attendee_information__process_reg_step',
            array($this, 'add_products')
        );
    }



    /**
     * @return array
     */
    public function products()
    {
        return (array)apply_filters(
            'FHEE__bc_ee_add_product_surcharge__products',
            $this->products
        );
    }



    /**
     * DO NOT EDIT!
     *
     * @param array $request_params
     * @return array
     */
    public function check_for_products(array $request_params)
    {
        if (isset($request_params['ee_reg_qstn'])) {
            foreach ($request_params['ee_reg_qstn'] as $registrations) {
                if (! empty($registrations)) {
                    foreach ($registrations as $QST_ID => $response) {
                        foreach ($this->products() as $product) {
                            if ($product['product_question_id'] === $QST_ID) {
                                // we found a product, so toggle the following filter switch to trigger processing
                                add_filter(
                                    'FHEE__bc_ee_add_product_surcharge__add_products', '__return_true'
                                );
                                return $request_params;
                            }
                        }
                    }
                }
            }
        }
        return $request_params;
    }



    /**
     * DO NOT EDIT!
     *
     * @param EE_SPCO_Reg_Step $reg_step
     * @return void
     * @throws EE_Error
     */
    public function add_products(EE_SPCO_Reg_Step $reg_step)
    {
        // apply the surcharge ?
        if (
            ! apply_filters('FHEE__bc_ee_add_product_surcharge__add_products', false)
            || ! $this->verify_objects($reg_step)
        ) {
            return;
        }
        $registrations = $this->checkout->transaction->registrations();
        $product_added = false;
        foreach ($registrations as $REG_ID => $registration) {
            if (! $registration instanceof EE_Registration) {
                continue;
            }
            $product = null;
            $product_qty = null;
            $product_qty_question_id = null;
            $answers = $registration->answers();
            foreach ($answers as $answer) {
                if ($answer instanceof EE_Answer) {
                    if ($product === null) {
                        $product = $this->get_product($answer->question_ID(), $answer->value());
                        $product_qty_question_id = isset($product['product_qty_question_id'])
                            ? $product['product_qty_question_id']
                            : null;
                    }
                    if ($answer->question_ID() === $product_qty_question_id) {
                        $product_qty = $answer->value();
                    }
                }
            }
            if ($product !== null && $product_qty !== null) {
                $product_added = $this->add_product($product, $product_qty, $registration)
                    ? true // toggle to true
                    : $product_added; // or maintain existing value
            }
        }
        if ($product_added) {
            $this->grand_total->recalculate_total_including_taxes();
        }
    }



    /**
     * DO NOT EDIT!
     *
     * @param EE_SPCO_Reg_Step $reg_step
     * @return bool
     * @throws EE_Error
     */
    private function verify_objects(EE_SPCO_Reg_Step $reg_step)
    {
        $this->checkout = $reg_step->checkout;
        // verify checkout && transaction
        if (
            ! (
                $this->checkout instanceof EE_Checkout
                && $this->checkout->transaction instanceof EE_Transaction
            )
        ) {
            return false;
        }
        // verify cart
        $cart = $this->checkout->cart;
        if (! $cart instanceof EE_Cart) {
            return false;
        }
        // verify grand total line item
        $this->grand_total = $cart->get_grand_total();
        if (! $this->grand_total instanceof EE_Line_Item) {
            return false;
        }
        $this->pre_tax_subtotal = EEH_Line_Item::get_pre_tax_subtotal($this->grand_total);
        return true;
    }



    /**
     * DO NOT EDIT!
     *
     * @param int    $product_id
     * @param string $option
     * @return null
     */
    private function get_product($product_id, $option)
    {
        foreach ($this->products() as $product) {
            if ($product['product_question_id'] === $product_id && isset($product['product_option_details'][$option])) {
                $product_details = $product['product_option_details'][$option];
                $product_details['product_question_id'] = $product['product_question_id'];
                $product_details['product_qty_question_id'] = $product['product_qty_question_id'];
                return $product_details;
            }
        }
        return null;
    }



    /**
     * DO NOT EDIT!
     *
     * @param array           $product
     * @param int             $product_qty
     * @param EE_Registration $registration
     * @return bool
     * @throws EE_Error
     */
    private function add_product(array $product, $product_qty = 0, EE_Registration $registration)
    {
        $product['code'] .= '-' . $registration->reg_code();
        $product['description'] = sprintf(
            esc_html_x(
                'for %1$s.',
                '{Product Name} for {Customer Name}',
                'event_espresso'
            ),
            $registration->attendee()->full_name()
        ) . ' ' . $product['description'];
        $product_added = $this->add_line_item(
            $product['code'],
            $product['name'],
            $product['description'],
            $product['unit_price'],
            $product['taxable'],
            $product_qty
        );
        if($product_added) {
            do_action(
                'AHEE__bc_ee_add_product_surcharge__add_product__product_added',
                $product,
                $product_qty,
                $registration
            );
        }
        return $product_added;
    }



    /**
     * DO NOT EDIT!
     *
     * @param string $product_code
     * @param string $product_name
     * @param string $product_description
     * @param float  $product_unit_price
     * @param bool   $product_taxable
     * @param        $product_qty
     * @return bool
     * @throws EE_Error
     */
    private function add_line_item(
        $product_code = '',
        $product_name = '',
        $product_description = '',
        $product_unit_price = 0.00,
        $product_taxable = true,
        $product_qty
    ) {
        // has surcharge already been applied ?
        $existing_surcharge = $this->grand_total->get_child_line_item($product_code);
        if ($existing_surcharge instanceof EE_Line_Item) {
            return false;
        }
        return $this->pre_tax_subtotal->add_child_line_item(
            EE_Line_Item::new_instance(
                array(
                    'LIN_name'       => $product_name,
                    'LIN_desc'       => $product_description,
                    'LIN_unit_price' => (float)$product_unit_price,
                    'LIN_quantity'   => $product_qty,
                    'LIN_is_taxable' => $product_taxable,
                    'LIN_order'      => 0,
                    'LIN_total'      => (float)$product_unit_price,
                    'LIN_type'       => EEM_Line_Item::type_line_item,
                    'LIN_code'       => $product_code,
                )
            )
        );
    }



}
// End of file  bc_ee_add_product_surcharge.php
