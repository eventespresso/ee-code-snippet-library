<?php
/**
 * Removes address fields from the Braintree gateway billing form
 * Braintree add-on version 1.0.3 or higher required
 * @param array $options
 * @var $form EE_Billing_Attendee_Info_Form
 */

function ee_braintree_remove_fields( $options, $form ) {
    if( $form instanceof EE_Billing_Attendee_Info_Form 
        && isset( $options[ 'name' ] ) 
        && $options[ 'name' ] === 'Braintree_Dropin_Billing_Form' 
    ) {
        $fields_to_remove = array(
            'email',
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
add_filter( 'FHEE__EE_Form_Section_Proper___construct__options_array', 'ee_braintree_remove_fields', 10, 2 );