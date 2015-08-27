<?php
/**
 * espresso_set_session_lifespan
 * set_session_lifespan to X minutes
 * @return int
 */
function espresso_set_session_lifespan() {
    return 5 * MINUTE_IN_SECONDS;
}
add_filter( 'FHEE__EE_Session__construct___lifespan', 'espresso_set_session_lifespan' );

// End of file ee_change_session_expiry_time.php