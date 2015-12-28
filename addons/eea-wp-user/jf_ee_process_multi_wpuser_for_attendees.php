<?php
/* 
 * Modifies the WP User Integration add-on for EE4 to create a new user account for each attendee within a transaction
 */

add_action( 'AHEE__EE_System__load_espresso_addons', 'jf_ee_wpuser_for_attendee_set_hooks', 11 );

function jf_ee_wpuser_for_attendee_set_hooks() {
    remove_action( 'AHEE__EE_Single_Page_Checkout__process_attendee_information__end', array( 'EED_WP_Users_SPCO', 'process_wpuser_for_attendee' ), 11 );
    add_action( 'AHEE__EE_Single_Page_Checkout__process_attendee_information__end', 'jf_ee_process_wpuser_for_attendee', 10, 2 );
    if ( EE_FRONT_AJAX ) {
        remove_action( 'AHEE__EE_Single_Page_Checkout__process_attendee_information__end', array( 'EED_WP_Users_SPCO', 'process_wpuser_for_attendee' ), 11 );
        add_action( 'AHEE__EE_Single_Page_Checkout__process_attendee_information__end', 'jf_ee_process_wpuser_for_attendee', 10, 2 );
    }
}

function jf_ee_process_wpuser_for_attendee( EE_SPCO_Reg_Step_Attendee_Information $spco, $valid_data ) {
    if ( class_exists( 'EED_WP_Users_SPCO' ) ) {

        //use spco to get registrations from the
        $registrations = EED_WP_Users_SPCO::_get_registrations( $spco );

        foreach ( $registrations as $registration ) {
            $user_created = FALSE;
            $att_id = '';
            $attendee = $registration->attendee();

            if ( ! $attendee instanceof EE_Attendee ) {
                //should always be an attendee, but if not we continue just to prevent errors.
                continue;
            }

            //if user logged in, then let's just use that user.  Otherwise we'll attempt to get a
            //user via the attendee info.
            if ( is_user_logged_in() ) {
                $user = get_userdata( get_current_user_id() );
            } else {
                //is there already a user for the given attendee?
                $user = get_user_by( 'email', $attendee->email() );

                //does this user have the same att_id as the given att?  If NOT, then we do NOT update because it's possible there was a family member or something sharing the same email address but is a different attendee record.
                $att_id = $user instanceof WP_User ? get_user_option( 'EE_Attendee_ID', $user->ID ) : $att_id;
                if ( ! empty( $att_id ) && $att_id != $attendee->ID() ) {
                    continue;
                }
            }

            $event = $registration->event();

            //no existing user? then we'll create the user from the date in the attendee form.
            if ( ! $user instanceof WP_User ) {
                //if this event does NOT allow automatic user creation then let's bail.
                if ( ! EE_WPUsers::is_auto_user_create_on( $event ) ) {
                    return; //no we do NOT auto create users please.
                }

                $password = wp_generate_password( 12, false );
                //remove our action for creating contacts on creating user because we don't want to loop!
                remove_action( 'user_register', array( 'EED_WP_Users_Admin', 'sync_with_contact' ) );
                $user_id = wp_create_user(
                    apply_filters(
                        'FHEE__EED_WP_Users_SPCO__process_wpuser_for_attendee__username',
                        $attendee->email(),
                        $password,
                        $registration
                    ),
                    $password,
                    $attendee->email()
                );
                $user_created = true;
                if ( $user_id instanceof WP_Error ) {
                    continue; //get out because something went wrong with creating the user.
                }
                $user = new WP_User( $user_id );
                update_user_option( $user->ID, 'description', apply_filters( 'FHEE__EED_WP_Users_SPCO__process_wpuser_for_attendee__user_description_field', __( 'Registered via event registration form', 'event_espresso' ), $user, $attendee, $registration ) );
            }

            // only do the below if syncing is enabled.
            if ( $user_created || EE_Registry::instance()->CFG->addons->user_integration->sync_user_with_contact ) {
                //remove our existing action for updating users via saves in the admin to prevent recursion
                remove_action( 'profile_update', array( 'EED_WP_Users_Admin', 'sync_with_contact' ) );
                wp_update_user(
                    array(
                        'ID'           => $user->ID,
                        'nickname'     => $attendee->fname(),
                        'display_name' => $attendee->full_name(),
                        'first_name'   => $attendee->fname(),
                        'last_name'    => $attendee->lname()
                    )
                );
            }

            //if user created then send notification and attach attendee to user
            if ( $user_created ) {
                do_action( 'AHEE__EED_WP_Users_SPCO__process_wpuser_for_attendee__user_user_created', $user, $attendee, $registration, $password );
                //set user role
                $user->set_role( EE_WPUsers::default_user_create_role( $event ) );
                update_user_option( $user->ID, 'EE_Attendee_ID', $attendee->ID() );
            } else {
                do_action( 'AHEE__EED_WP_Users_SPCO__process_wpuser_for_attendee__user_user_updated', $user, $attendee, $registration );
            }

            //failsafe just in case this is a logged in user not created by this system that has never had an attendee record.
            $att_id = empty( $att_id ) ? get_user_option( 'EE_Attendee_ID', $user->ID ) : $att_id;
            if ( empty( $att_id ) && EED_WP_Users_SPCO::_can_attach_user_to_attendee( $attendee, $user ) ) {
                update_user_option( $user->ID, 'EE_Attendee_ID', $attendee->ID() );
            }
        } //end registrations loop
    }
}