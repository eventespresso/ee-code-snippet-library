<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

//* Moves the social share buttons so they're before the registration details on the Thank You page

add_action(
	'AHEE__EED_Thank_You_Page__init_end',
	'jf_ee_move_social_buttons'
);
function jf_ee_move_social_buttons() {
    if (class_exists('EED_Social_Buttons')) {
        add_action(
            'AHEE__thank_you_page_overview_template__content',
            array(
                'EED_Social_Buttons',
                'thank_you_page_buttons'
            )
        );
        remove_action(
            'AHEE__thank_you_page_overview_template__bottom',
            array(
                'EED_Social_Buttons',
                'thank_you_page_buttons'
            )
        );
    }
}