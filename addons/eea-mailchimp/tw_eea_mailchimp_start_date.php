<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file.

//This function passes the next upcoming datetime start_date within the subscribe call to MailChimp.
//You need to add a EVENTDATE merge var to your mailchimp list for this to work.

function tw_eea_mailchimp_start_date( $subscribe_args, $registration, $EVT_ID ) {

	//Check we have a valid EE Registration
	if( $registration instanceof EE_Registration ) {
		
		//Pull the merge_vars array from $subscribe_args.
		$merge_vars = $subscribe_args['merge_vars'];

		//Pull the datetimes assigned to this ticket that have a start date greater than today.
		$where = array( 'DTT_EVT_start' => array( '>=', current_time( 'mysql' ) ) );

		$datetimes = $registration->ticket()->datetimes( array( $where,	'order_by'=> array('DTT_EVT_start' => 'ASC' ) ) );

		//Use the first datetime.
		$datetime = reset($datetimes);

		//Add the 'EVENTDATE' merge var and use the datetimes start_date for the value.
		$merge_vars['EVENTDATE'] = $datetime->start_date( 'Y-m-d' );

		//Add the merge vars back into the subscriptions args.
		$subscribe_args['merge_vars'] = $merge_vars;

	}
	
	return $subscribe_args;

}
add_filter('FHEE__EE_MCI_Controller__mci_submit_to_mailchimp__subscribe_args', 'tw_eea_mailchimp_start_date', 10, 3);
