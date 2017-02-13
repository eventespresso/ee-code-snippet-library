<?php
/**
 * Removes address fields from the MasterCard Internet Gateway Service billing form
 * MiGS add-on version 1.0.0 or higher required
 * @param array $options
 * @var $form EE_Billing_Attendee_Info_Form
 */

function loc_ee_remove_address_fields_from_migs_billing_form( $options, $form ) {
    if( $form instanceof EE_Billing_Attendee_Info_Form 
        && isset( $options[ 'name' ] ) 
        && $options[ 'name' ] === 'MIGS_Billing_Form' 
    ) {
        $fields_to_remove = array(
            'address', 
            'address2', 
            'city', 
            'state', 
            'country',
            'zip', 
            'phone' 
        );      
        foreach( $fields_to_remove as $field ) {
            unset( $options[ 'subsections' ][ $field ]);
        }
    }
    return $options;
}
add_filter( 'FHEE__EE_Form_Section_Proper___construct__options_array', 'loc_ee_remove_address_fields_from_migs_billing_form', 10, 2 );