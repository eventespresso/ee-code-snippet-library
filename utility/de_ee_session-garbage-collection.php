<?php
//you could add this as a callback on a wp_cron schedule, or somewhere else.
public function de_ee_transient_garbage_collection() {
		global $wpdb;

		$now = time();
		$query = "DELETE FROM t1, t2 USING $wpdb->options AS t1 JOIN $wpdb->options AS t2 ON t2.option_name = replace( t1.option_name, '_timeout', '' )  WHERE ( t1.option_value - 86400 ) < $now AND t1.option_name LIKE '\_transient\_timeout\_ee\_ssn\_%'";
		$wpdb->query( $query );
	}
