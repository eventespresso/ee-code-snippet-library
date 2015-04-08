<?php

// use optional paypal account via a meta key in EE4
// note: use the meta key: paypal_id
// the default paypal ID set in Payment Methods will be used if Multi Event Registration is activated

add_filter( 'FHEE__EEG_Paypal_Standard__set_redirection_info__arguments', 'jf_ee_paypal_account_meta_key_override' );

function jf_ee_paypal_account_meta_key_override( $redirect_args ) {
    if ( ! defined( 'EE_MER_PATH' ) ) {
        $checkout = EE_Registry::instance()->SSN->checkout();
        if ( $checkout instanceof EE_Checkout ) {
            $transaction = $checkout->transaction;
            if ( $transaction instanceof EE_Transaction ) {
               foreach ( $transaction->registrations() as $registration ) {
                    if ( $registration instanceof EE_Registration ) {
                        $event = $registration->event();
                        if ( $event instanceof EE_Event ) {
                            $meta_key_value = get_post_meta( $event->ID(), 'paypal_id', true );
                            $clean_email = sanitize_email( $meta_key_value );
                            if ( isset( $redirect_args[ 'business' ] ) && ! empty( $clean_email ) && $clean_email == $meta_key_value ) {
                                $redirect_args[ 'business' ] = $meta_key_value;
                            }
                        }
                    }
                }
            }
        }
    }
    return $redirect_args; 
}