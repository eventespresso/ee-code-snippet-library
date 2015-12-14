<?php

function jf_add_target_blank_reciept_link() {
    ?>
    <script>
    jQuery(document).ready(function($) {
      $(".ee-attention").click(function() {
        var recieptLink = $(this).find("a");
        recieptLink.attr("target", "_blank");
        window.open(recieptLink.attr("href"));
        return false;
      });
    });
    </script>
    <?php
}
add_action( 'AHEE__thank_you_page_overview_template__bottom', 'jf_add_target_blank_reciept_link' );