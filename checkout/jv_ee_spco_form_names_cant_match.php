<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

function ee_spco_names_cant_match(){
  wp_add_inline_script(
    'single_page_checkout',
    'jQuery(document).ready(function ($) {
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
  ');
};
add_action( 'wp_enqueue_scripts', 'ee_spco_names_cant_match', 11 );