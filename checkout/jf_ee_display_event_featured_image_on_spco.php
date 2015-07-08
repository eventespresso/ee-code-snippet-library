<?php

function jf_ee_display_event_featured_image_on_spco(){
    $checkout = EE_Registry::instance()->SSN->checkout();
    if ( $checkout instanceof EE_Checkout ) {
        $transaction = $checkout->transaction;
        if ( $transaction instanceof EE_Transaction ) {
            foreach ( $transaction->registrations() as $registration ) {
                if ( $registration instanceof EE_Registration ) {
                    $event = $registration->event();
                    if ( $event instanceof EE_Event ) {
                        $events[ $event->ID() ] = $event;
                        $image = $event->feature_image( 'full' );
                    }
                }
            }
        echo $image;   
        }
    }
}

add_action( 'AHEE__SPCO__before_registration_steps', 'jf_ee_display_event_featured_image_on_spco' );