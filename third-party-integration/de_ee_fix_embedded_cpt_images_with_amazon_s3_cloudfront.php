<?php
/**
 * If you are using the Amazon S3 Cloudfront plugin and have it set to upload your media to S3 and then remove from your 
 * `uploads` directory, you'll notice that any embedded EE cpt content in other content (i.e. Venue information embedded with 
 * the Event Description) won't show any images that might be in the content field for that cpt.  This is because the AS3CF 
 * plugin filters `the_content` too early for catching EE content embedded this way.
 * This code snippet will fix that.  Just add this to a site-specific plugin (recommended) or in your theme's 
 * `functions.php` file.
 */
 
 add_action( 'aws_init, 'de_ee_fix_content_filters_for_as3cf', 15);
 function de_ee_fix_content_filters_for_as3cf() {
    	//add and remove the_content filters at later priority so as3cf is filtering urls
	global $as3cf;
	if (! isset($as3cf->filter_local) || ! $as3cf->filter_local instanceof AS3CF_Local_To_S3) {
		return;
	}

	remove_filter('the_content', array($as3cf->filter_local, 'filter_post'), 100);
	remove_filter('the_excerpt', array($as3cf->filter_local, 'filter_post'), 100);
	add_filter('the_content', array($as3cf->filter_local, 'filter_post'), 140);
	add_filter('the_excerpt', array($as3cf->filter_local, 'filter_post'), 140);
 }
