/**
*
* Tested with EE 4.6.27p 
*
* Ways to remove EE4 Event and Venue meta boxes
* This is unsupported so use caution and test before applying to your own sites.
*
* PLEASE NOTE:  Currently EE expects data from the forms in those boxes when an event/venue is saved, 
* and will likely throw an error, not allow saving, or the event registration will not function if certain data is not present.  
* So removing the metaboxes completely will likely result in errors.  
* If you want to retain any data that's already on an event, or prevent the errors, 
* you will need to ensure you print hidden form fields in the loaded page that have the existing data 
* (or default data on new events/venues) OR you will need to hook into the save/update process 
* to handle when there's no expected data (and either preserve existing data in related tables 
* or add default data that EE expects for other systems). 
* If you need further help with this use case, it falls outside the services we provide with a support 
* license and you will need to request custom development for more detailed assistance.
*  
* Please use this provided code with caution and test your changes thoroughly on a development server first!
* 
**/

function remove_em_all_venues() {
  // ee related
  remove_meta_box( 'espresso_venue_gmap_options', 'espresso_venues', 'side' );
  remove_meta_box( 'espresso_venue_virtual_loc_options', 'espresso_venues', 'side' );
  remove_meta_box( 'espresso_venue_categoriesdiv', 'espresso_venues', 'side' );
  remove_meta_box( 'espresso_venue_address_options', 'espresso_venues', 'side' );
}
add_action( 'add_meta_boxes', 'remove_em_all_venues', 20);

function remove_em_all_events() {
  
  // options, do not remove!
  // remove_meta_box( 'espresso_event_editor_event_options', 'espresso_events', 'side' );

  // categories
  remove_meta_box( 'espresso_event_categoriesdiv', 'espresso_events', 'side' );

  // primary questions, do not remove!
  // remove_meta_box( 'espresso_events_Registration_Form_Hooks_Extend_primary_questions_metabox', 'espresso_events', 'side' );

  // additional qquestions
  remove_meta_box( 'espresso_events_Registration_Form_Hooks_Extend_additional_questions_metabox', 'espresso_events', 'side' );

  // venue
  remove_meta_box( 'espresso_events_Venues_Hooks_venue_metabox_metabox', 'espresso_events', 'normal' );

  // tickets, do not remove!
  // remove_meta_box( 'espresso_events_Pricing_Hooks_pricing_metabox_metabox', 'espresso_events', 'normal' );

  // notifications, do not remove!
  // remove_meta_box( 'espresso_events_Messages_Hooks_Extend_messages_metabox_metabox', 'espresso_events', 'advanced' );

  // page template
  remove_meta_box( 'page_templates', 'espresso_events', 'side' );
  
  // tags
  remove_meta_box( 'tagsdiv-post_tag', 'espresso_events', 'side' );

  // featured image
  remove_meta_box( 'postimagediv', 'espresso_events', 'side' );

  // post excerpt
  remove_meta_box( 'postexcerpt', 'espresso_events', 'normal' );

  // custom fields
  remove_meta_box( 'postcustom', 'espresso_events', 'normal' );

  // discussion
  remove_meta_box( 'commentstatusdiv', 'espresso_events', 'normal' );

  // comments
  remove_meta_box( 'commentsdiv', 'espresso_events', 'normal' );

  // slug
  remove_meta_box( 'slugdiv', 'espresso_events', 'normal' );

  // author
  remove_meta_box( 'authordiv', 'espresso_events', 'normal' );

}
add_action( 'admin_enqueue_scripts', 'remove_em_all_events', 30);