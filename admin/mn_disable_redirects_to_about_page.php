<?php
/**
 * Makes it so the site admin is never redirected to the EE about page (mentioning what new features and bug fixes
 * have been added to EE). Works with EE 4.9.21.p or higher
 */
add_filter(
    'FHEE__EE_System__redirect_to_about_ee__do_redirect',
    function( $do_redirect ) {
        return false;
    }
);