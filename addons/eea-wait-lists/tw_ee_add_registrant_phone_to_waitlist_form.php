<?php
/* 
 * Modifies the waitlist form to include a phone number question, the answer will be saved to the EE Contact created during the registration.
 */
add_filter('FHEE__EventEspresso_WaitList_domain_services_forms_WaitListForm__waitListFormOptions__form_options', 'tw_ee_add_phone_to_waitlist_form', 10, 5);
function tw_ee_add_phone_to_waitlist_form($form_options, $event, $tickets, $wait_list_spaces_left, $WaitListForm) {
    // First check if the form_options has a 'hidden_inputs' sub-section that we can use
    if( $form_options['subsections']['hidden_inputs'] instanceof EE_Form_Section_Proper ) {
        // Sanity check just incase the registration_phone field has already been set (by core code for example).
        if( empty($form_options['subsections']['hidden_inputs']['registrant_phone']) ) {
            // Build out the new registration_phone text input using the same format as the other fields.
            $registration_phone = array('registrant_phone' => new EE_Text_Input(
                array(
                    'html_label_text'       => esc_html__('Phone Number', 'event_espresso'),
                    'html_label_class'      => 'small-text grey-text',
                    'other_html_attributes' => ' placeholder="'
                                               . esc_html__(
                                                   'please enter your phone number',
                                                   'event_espresso'
                                               )
                                               . '"',
                    'html_class'            => '',
                    'default'               => '',
                    'required'              => true,
                )
            ));

            // Add the registration_phone array as a subsection of hidden_inputs,
            // target the 'registrant_email' field to add this new field just after it.
            $form_options['subsections']['hidden_inputs']->add_subsections(
                $registration_phone,
                'registrant_email',
                false
            );
        }
    }
    // return $form_options regardless of what we've done above
    return $form_options;
}

add_action('AHEE__EventEspresso_WaitList_domain_services_commands_CreateWaitListRegistrationsCommandHandler__createRegistrations', 'tw_save_waitlist_phone_to_contact', 10, 2);
function tw_save_waitlist_phone_to_contact( $registrations_created_array, $attendee)
{
    // Technically at this point $_POST should always just be an array with a single element of 'event-wait-list-{eventid}'
    // But lets cover all bases and work through all elements until we find that key.
    foreach ($_POST as $post_key => $post_value) {
        if (strpos($post_key, 'event-wait-list-') !== false) {
            // We have an element with the event-wait-list-{eventID} key, so check it has 'hidden_inputs'
            if (! empty($post_value['hidden_inputs']) ) {
                // Now confirm we have a value for 'registrant_phone'
                if (! empty($post_value['hidden_inputs']['registrant_phone']) ) {
                    // Sanatize the value and save it as the attendees phone number
                    $attendee->set_phone(sanitize_text_field($post_value['hidden_inputs']['registrant_phone']));
                    $attendee->save();
                }
            }
        }
    }
}