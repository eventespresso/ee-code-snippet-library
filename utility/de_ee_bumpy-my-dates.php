<?php
/**
 * Trying to Test EE4 but all of your example dates have expired ?Use this function to bump all of your event and ticket dates forward in time by 1 month.
 */

class EEBumpDatesForward {

	/**
	 * constant and properties related to wp cron schedules.
	 */
	const bumpDatesScheduleInterval = 'daily';
	private $_scheduleIntervals;


	public function __construct() {
		$this->_set_schedule_intervals();
		$this->_set_schedules();
	}


	private function _set_schedule_intervals() {
		$this->scheduleIntervals = array(
			'bumpEspressoEvents' => self::bumpDatesScheduleInterval
			);
	}



	private function _set_schedules() {
		if ( ! wp_next_scheduled( 'refresh_espresso_events' ) ) {
			wp_schedule_event( time(), $this->_scheduleIntervals['bumpEspressoEvents'], 'refresh_espresso_events' );
		}
		add_action( 'refresh_espresso_events', array( $this, 'refresh_espresso_events' ), 10 );
	}


	/**
	 * contains the code for bumping events forward by the set interval... also takes care of bumping tickets by a similar interval for each event.
	 * triggered by the wp-cron schedule.
	 *
	 * @return void
	 */
	public function refresh_espresso_events() {
		global $wpdb;
		//setup the sql
		$SQLs = array();

		//first select all the datetimes that have expired so we use their ids
		$exp_dtts_query = 'SELECT DTT_ID from ' . $wpdb->prefix . 'esp_datetime WHERE DTT_deleted = 0 AND DTT_EVT_end < NOW()';

		$expired_datetime_ids = $wpdb->get_col( $exp_dtts_query );

		//if no expired datetimes then get out!
		if ( empty( $expired_datetimes ) )
			return;

		$datetimes_in = ' IN(' . implode( ',', $expired_datetimes ) . ')';

		//datetimes using our datetime_ids!
		$SQLs[] = 'UPDATE ' . $wpdb->prefix . 'esp_datetime SET DTT_EVT_start = DATE_ADD( DTT_EVT_start, INTERVAL 1 MONTH ), DTT_EVT_end = DATE_ADD( DTT_EVT_end, INTERVAL 1 MONTH ) WHERE DTT_ID' . $datetimes_in;

		//tickets
		$SQLs[] = 'UPDATE SET t.TKT_end_date = DATE_ADD( t.TKT_end_date, INTERVAL 1 MONTH ) FROM ' . $wpdb->prefix . 'esp_ticket as t  LEFT JOIN ' . $wpdb->prefix . 'esp_datetime_ticket as dt ON dt.TKT_ID = t.TKT_ID WHERE TKT_deleted = 0 and dt.DTT_ID' . $datetimes_in;

		foreach ( $SQLs as $SQL ) {
			$wpdb->query( $SQL );
		}

	}
}

$eebumpdatesup = new EEBumpDatesForward();
