<?php
/**
 * @package EE_Event_Category_Widget
 * @version 1.0
 */
/*
Plugin Name: Event Espresso Event Category Widget
Plugin URI: https://github.com/eventespresso/ee-code-snippet-library
Description: This adds a WordPress widget to include a dropdown to view lists of events filtered by event category
Author: Josh Feck
Version: 1.0
Author URI: https://eventespresso.com
*/



/**
 * Adds EE_Event_Category_Widget widget.
 */
class EE_Event_Category_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'ee_event_category_widget', // Base ID
            esc_html__( 'Event Espresso Category Select', 'text_domain' ), // Name
            array( 'description' => esc_html__( 'A widget that includes a dropdown to view lists of events filtered by event category', 'text_domain' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        ?>
        <li style="list-style: none" id="event-categories">
            <form id="category-select" class="category-select" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
        
                <?php
                $args = array(
                    'show_option_none' => __( 'Select event category' ),
                    'value_field'      => 'slug',
                    'show_count'       => 0,
                    'taxonomy'         => 'espresso_event_categories',
                    'orderby'          => 'name',
                    'name'             => 'espresso_event_categories',
                    'echo'             => 0,
                );
                ?>
        
                <?php $select  = wp_dropdown_categories( $args ); ?>
                <?php $replace = "<select$1 onchange='return this.form.submit()'>"; ?>
                <?php $select  = preg_replace( '#<select([^>]*)>#', $replace, $select ); ?>
        
                <?php echo $select; ?>
        
                <noscript>
                    <input type="submit" value="View" />
                </noscript>
        
            </form>
        </li>
        <?php
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'View Events by Category', 'text_domain' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php 
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

} // class EE_Event_Category_Widget


// register EE_Event_Category_Widget widget
function register_ee_event_category_widget() {
    register_widget( 'EE_Event_Category_Widget' );
}
add_action( 'widgets_init', 'register_ee_event_category_widget' );
