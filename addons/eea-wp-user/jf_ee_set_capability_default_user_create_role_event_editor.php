<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file
/* 
 * This code snippet is for the EE4 User Integration add-on.
 * Using the filter here, you can specify exactly which user roles you want to appear as choices
 * in the event editor's User Integration Settings box. In this example, it will display only the S2Member Levels 1 & 2,
 * and the Subscriber role.
 */

add_filter( 'FHEE__EED_WP_Users_Admin__event_editor_metabox__wp_user_form_content', 'jf_ee_set_capability_default_user_create_role_event_editor', 11 );
function jf_ee_set_capability_default_user_create_role_event_editor( $array ) {
    global $post;
    $array['default_user_create_role'] = new EE_Select_Input(
        array( 
            's2member_level2' => 's2Member Level 2',
            's2member_level1' => 's2Member Level 1',
            'subscriber' => 'Subscriber',
            // add more roles here;
            ),
        array(
            'html_label_text' => __('Default role for auto-created users:', 'event_espresso' ),
            'html_help_text' => __( 'When users are auto-created, what default role do you want them to have?', 'event_espresso' ),
            'default' => EE_WPUsers::default_user_create_role( $post->ID ),
            'display_html_label_text' => true
            )
        );
    return $array;
}