<?php

/**
 * Allows accent characters in URLs. I really don't know why we don't allow this to begin with. I think some servers just
 * USED to not support it.
 * @param $modified_title string
 * @param $original_title string
 * @param $context string
 * @return string
 */
function mn_sanitize_title($modified_title, $original_title, $context)
{
    // the $modified_title may have had accents removed, but not the $original_title
    return $original_title;
}
// set this filter to run BEFORE WP already ran the title through `sanitize_title_with_dashes`
add_filter('sanitize_title', 'mn_sanitize_title', 5, 3);