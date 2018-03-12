<?php
/**
 * This code snippet adds a new question type that uses
 * one of your site's Custom Post Types as values for the input.
 * This snippet is based off Michael's "add question type" snippets found at:
 *  /ee-code-snippet-library/checkout/mn_add_question_type.php
 */

// EDIT THE FOLLOWING THREE VARIABLES

// the slug used to register the custom post type with WordPress
$custom_post_type_slug = 'espresso_venues';
// the name you want to appear for this question type in the Event Espresso - Registration Form admin
$custom_post_type_name = esc_html__('Venue', 'event_espresso');
// full server path to the CustomPostTypeInput class (see below)
$path_to_custom_post_type_input = __DIR__ . '/CustomPostTypeInput.php';

// THEN MOVE THE CODE BELOW THE FOLLOWING TWO FILTERS INTO A NEW FILE

// adds new question type to the Event Espresso - Registration Form admin page
add_filter(
    'FHEE__EEM_Question__construct__allowed_question_types',
    function ($question_types) use ($custom_post_type_slug, $custom_post_type_name)
    {
        $question_types[ $custom_post_type_slug ] = $custom_post_type_name;
        return $question_types;
    }
);
// generates the actual question input when used in a registration form
add_filter(
    'FHEE__EE_SPCO_Reg_Step_Attendee_Information___generate_question_input__default',
    function ($input, $question_type, $question_obj, $options)
    use ($custom_post_type_slug, $path_to_custom_post_type_input)
    {
        if (! $input && $question_type === 'espresso_venues') {
            require $path_to_custom_post_type_input;
            $options['post_type'] = array($custom_post_type_slug);
            $input                = new CustomPostTypeInput($options);
        }
        return $input;
    },
    10,
    4
);

// IMPORTANT !!!
// MOVE THE FOLLOWING INTO A NEW FILE NAMED: 'CustomPostTypeInput.php'
// AND update the $path_to_custom_post_type_input variable above to point to it

/**
 * Class CustomPostTypeInput
 * Description
 *
 * @author  Brent Christensen
 * @since   $VID:$
 */
class CustomPostTypeInput extends EE_Select_Input
{

    /**
     * @param array $input_settings
     */
    public function __construct($input_settings = array())
    {
        $custom_post_type_query = new WP_Query();
        $custom_post_types      = $custom_post_type_query->query(
            array(
                'post_type' => is_array($input_settings['post_type'])
                    ? $input_settings['post_type']
                    : array($input_settings['post_type'])
            )
        );
        $answer_options         = array();
        foreach ($custom_post_types as $custom_post_type) {
            if ($custom_post_type instanceof WP_Post) {
                $answer_options[ $custom_post_type->ID ] = $custom_post_type->post_title;
            }
        }
        parent::__construct($answer_options, $input_settings);
    }
}
