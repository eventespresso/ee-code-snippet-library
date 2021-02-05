<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file
    // change page.php (line 10) to match the name of the desired template
    // if using a child theme, change get_template_directory() to get_stylesheet_directory()

add_filter('single_template', 'jf_ee_custom_post_type_template');
function jf_ee_custom_post_type_template($single_template) {
  global $post;
  if ($post->post_type == 'espresso_events') {
    $single_template = get_template_directory() . '/page.php';
  }
  return $single_template;
}
