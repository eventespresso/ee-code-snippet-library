<?php
// Please do NOT include the opening php tag, except of course if you're starting with a blank file

// Disable email match check for all users
add_filter( 'EED_WP_Users_SPCO__verify_user_access__perform_email_user_match_check', '__return_false' );