# How to log errors thrown by wp_mail

This plugin writes errors thrown by wp_mail to your error log, however there are some steps you need to take to enable this.

First, add this snippet to your `wp-config.php` file:

```
// Enable WP_DEBUG mode
define( 'WP_DEBUG', true );
if ( WP_DEBUG ) {
        @ini_set( 'display_errors', 0 );
        define( 'WP_DEBUG_LOG', true );
        define( 'WP_DEBUG_DISPLAY', false );
}
```

See https://eventespresso.com/wiki/troubleshooting-checklist/#wpdebug

You add that code above the `/* That's all, stop editing! Happy blogging. */` comment in the above file ([example](https://monosnap.com/file/eGdAz5tOSjacoQ9h5BiQieCGAUhiN8)), usually you can find this code: `define( 'WP_DEBUG', false );` somewhere in the file, replace that with the snippet above.

That will tell WordPress to log any errors/notices/warnings to `/wp-content/debug.log` so then the code in this plugin write to that file.

Once you have added the above, add the code in `ee-wp-mail-log-errors.php` to a custom fucntions plugin, we have some documentatio on creating one here:

https://eventespresso.com/wiki/create-site-specific-plugin-wordpress-site/ 