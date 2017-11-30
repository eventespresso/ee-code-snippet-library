<?php 
if ( ! defined('ABSPATH')) exit('No direct script access allowed');

//This function sorts the message templates shown in the 'Notifications' section selection inpurts alphabetically.
function tw_ee_event_editor_jquery_message_templates_sort(){
    wp_add_inline_script(
        'event_editor_js',
        'jQuery( document ).ready(function() {
           jQuery(".message-template-selector").each(function() {
                // Save the currently selected option.
                var selectedTemplate = jQuery(this).val();
         
                // Sort all the options by text.
                jQuery(this).html(jQuery("option", jQuery(this)).sort(function(a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1
                }));
         
                // Set the previously selected option.
                jQuery(this).val(selectedTemplate);
            });
        });'
    );
}
add_action( 'admin_enqueue_scripts', 'tw_ee_event_editor_jquery_message_templates_sort', 20 );