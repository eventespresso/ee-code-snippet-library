/* A rule that will check if the First and Last Name are not equal. 
If true, the "First and Last Name can not match message is displayed". */

/* Make sure to add this  script to your theme child folder and enqueue the script with 'single_page_checkout' as a dependency with the following wp_enqueue script WP Function under the functions.php file:
function ee4_nnamescantmatch_script(){
	wp_enqueue_script( 'ee4-namescantmatch', get_template_directory_uri() . '/ee4-namescantmatch.js', array( 'single_page_checkout' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'ee4_nnamescantmatch_script' );
*/

jQuery(document).ready(function ($) {
  const namesCantMatchErrorMessage = "First and Last Name can not match";
  const namesCantMatchRule = {
    namesCantMatch: true,
    messages: { namesCantMatch: namesCantMatchErrorMessage },
  };

  $.validator.addMethod(
    "namesCantMatch",
    function (value, lname) {
      const firstNameID = $(lname).data("fname");
      return $(firstNameID).val() !== value;
    },
    namesCantMatchErrorMessage
  );

  $(".spco-attendee-panel-dv").each(function (index, attendee) {
    const $fname = $(attendee).find(".ee-reg-qstn-fname");
    const $lname = $(attendee).find(".ee-reg-qstn-lname");
    $lname
      .data("fname", "#" + $fname.attr("id"))
      .rules("add", namesCantMatchRule);
  });
});
