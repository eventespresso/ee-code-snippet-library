<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

// don't display promo code input for specific events by setting Custom field to coupon == no

add_action( 'AHEE__EE_SPCO_Reg_Step_Payment_Options__generate_reg_form__event_requires_payment', 'jf_ee_promo_field_meta_key_override', 10 );
function jf_ee_promo_field_meta_key_override() {
    if ( ! defined( 'EE_MER_PATH' ) ) {
        $checkout = EE_Registry::instance()->SSN->checkout();
        if ( $checkout instanceof EE_Checkout ) {
            $transaction = $checkout->transaction;
            if ( $transaction instanceof EE_Transaction ) {
               foreach ( $transaction->registrations() as $registration ) {
                    if ( $registration instanceof EE_Registration ) {
                        $event = $registration->event();
                        if ( $event instanceof EE_Event ) {
                            // in this case, the Custom Field name is 'coupon' and value is 'no'
                            $meta_key_value = get_post_meta( $event->ID(), 'coupon', true );
                            if ( ! empty( $meta_key_value ) && $meta_key_value == 'no' ) {
                                remove_action( 'FHEE__EE_SPCO_Reg_Step_Payment_Options___display_payment_options__before_payment_options', array( 'EED_Promotions', 'add_promotions_form_inputs' ));
                            }
                        }
                    }
                }
            }
        }
    }
}