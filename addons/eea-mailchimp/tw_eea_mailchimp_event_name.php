<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file.

//This function passes the event name to a merge var called 'EVENTNAME' which needs to be setup within your list.

function tw_eea_mailchimp_event_name( $subscribe_args, $registration, $EVT_ID ) {

    //Pull the event using EVT_ID.
    $event = EEM_Event::instance()->get_one_by_ID($EVT_ID);

    if( $event instanceof EE_Event ) {
        //Pull the merge_vars array from $subscribe_args.
        $merge_vars = $subscribe_args['merge_vars'];
        //Add the 'EVENTNAME' merge var.
        $merge_vars['EVENTNAME'] = $event->name();
        //Add the merge vars back into the subscriptions args.
        $subscribe_args['merge_vars'] = $merge_vars;
    }

    //Return the subscribe args.
    return $subscribe_args;

}
add_filter('FHEE__EE_MCI_Controller__mci_submit_to_mailchimp__subscribe_args', 'tw_eea_mailchimp_event_name', 10, 3);