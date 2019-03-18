<?php //Please do not include the opening PHP tag if you already have one.

// write_log function, writes to debug.log
if (! function_exists('write_log')) {
    function write_log($log)
    {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}

// Log errors thrown during wp_mail
add_action('wp_mail_failed', 'tw_log_wp_mail_errors', 10, 1);
function tw_log_wp_mail_errors($wp_error)
{
    write_log($wp_error);
}
