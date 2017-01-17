<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file.

//This function passes the next upcoming datetime start_date within the subscribe call to MailChimp.
//You need to add a EVENTDATE merge var to your mailchimp list for this to work.

function tw_eea_mailchimp_start_date( $subscribe_args, $registration, $EVT_ID ) {
	//Check we have a valid EE Registration
	if( $registration instanceof EE_Registration ) {

		//Pull the datetimes assigned to this ticket that have a start date greater than today.
		$where = array( 'DTT_EVT_start' => array( '>=', current_time( 'mysql' ) ) );
		$datetimes = $registration->ticket()->datetimes( array( $where,	'order_by'=> array('DTT_EVT_start' => 'ASC' ) ) );

		//Use the first datetime.
		$datetime = reset($datetimes);

		//merge_vars or merge_fields?		
		if( isset( $subscribe_args['merge_vars'] ) ) {
			//Backwards compatability with older version of the MailChimp Add-on.
			
			//Pull the merge_vars array from $subscriber_args (array)
			$merge_vars = $subscribe_args['merge_vars'];
			//Add the 'EVENTDATE' merge var and use the datetimes start_date for the value.
			$merge_vars['EVENTDATE'] = $datetime->start_date( 'Y-m-d' );
			//Add the merge vars back into the subscriptions args.
			$subscribe_args['merge_vars'] = $merge_vars;
		
		} elseif( isset( $subscribe_args['merge_fields'] ) ) {
			//The Mailchimp add-on version 2.4.0+ changed to use v3 of the MaiLChimp API
			//$subcriber_ars['merge_vars'] no longer exists and it uses $subscribe_args['merge_fields']

			//Pull the merge_fields array from $subscribe_args (stdClass)
			$merge_fields = $subscribe_args['merge_fields'];
			//Add the 'EVENTDATE' merge field and use the datetimes start_date for the value.
			$merge_fields->EVENTDATE = $datetime->start_date( 'Y-m-d' );
			//Add the merge vars back into the subscriptions args.
			$subscribe_args['merge_fields'] = $merge_fields;
		}
	}

	return $subscribe_args;
}
add_filter('FHEE__EE_MCI_Controller__mci_submit_to_mailchimp__subscribe_args', 'tw_eea_mailchimp_start_date', 10, 3);