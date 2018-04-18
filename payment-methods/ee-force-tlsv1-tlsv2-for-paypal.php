<?php
/**
 * Add the following snippet to your theme's functions.php file or ideally in a site-specific plugin.
 * This only affects paypal related payment methods, you can make it site wide by just removing the paypal url condition
 */
function ee_force_tls_for_curl($handle, $r, $url) {
    if (! $handle ) {
      return;
    }
    if (strstr($url, 'https://') && strstr($url, '.paypal.com')) {
        if (OPENSSL_VERSION_NUMBER >= 0x1000100f) {
            if (! defined('CURL_SSLVERSION_TLSv1_2')) {
                // Note the value 6 comes from its position in the enum that
                // defines it in cURL's source code.
                define('CURL_SSLVERSION_TLSv1_2', 6); // constant not defined in PHP < 5.5
            }

            curl_setopt($handle, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        } else {
            if (! defined('CURL_SSLVERSION_TLSv1')) {
                define('CURL_SSLVERSION_TLSv1', 1); // constant not defined in PHP < 5.5
            }
            curl_setopt($handle, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        }
    }
}
// priority is important here because it will override the code already in ee core for this.
add_action( 'http_api_curl', 'ee_force_tls_for_curl', 15, 3 );
