<?php

function jf_ee_remove_payment_step( $reg_steps ) {
    foreach( $reg_steps as $step => $value ){
        if( $value['slug'] == 'payment_options' ) {
            unset( $reg_steps[$step] );
        }
    }
    return $reg_steps;
}

add_filter ( 'AHEE__SPCO__load_reg_steps__reg_steps_to_load', 'jf_ee_remove_payment_step' );
