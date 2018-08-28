<?php
/*
Plugin Name: Event Espresso - REST API off-site event listing shortcode
Description: Adds an [other_site_event_list] shortcode to allow displaying events from another WordPress site. Usage example: [other_site_event_list url=https://demoee.org/use-cases]
Author: Josh Feck
Version: 1.0
*/

function my_rest_api_event_list( $atts ) {
    $atts = shortcode_atts( array(
        'url' => ''
    ), $atts, 'other_site_event_list' );
    $curdate_utc = date("Y-m-d H:i:s");
    if($atts['url'] == '') {
        return;
    }
    $data_url = esc_url($atts['url']) . "/wp-json/ee/v4.8.36/events?calculate=image_medium_large&include=Datetime&where[Datetime.DTT_EVT_start][]=>&where[Datetime.DTT_EVT_start][]=" . urlencode($curdate_utc);
    $json = file_get_contents($data_url, true); 
    $events = json_decode($json, true); 
    $count = count( $events ); 
    $html = '<div id="embedded-events" style="max-width: 700px; margin: 0 auto;">';
    if ($count > 0){
        foreach ($events as $event){
            $html .= '<div class="embedded-event">';
            $html .= '<h3><a href="' . $event[ 'link' ] . '">' . $event[ 'EVT_name' ] . '</a></h3>';
            $html .= '<div style="text-align:center">';
            $featured_image_url = $event['_calculated_fields']['image_medium_large']['url'];
            $html .= $featured_image_url ? '<a href="' . esc_url( $event['link'] ). '"><img src="' . esc_url( $featured_image_url ) . '" /></a>' : '';
            $html .= '</div>';
            $html .= '<ul>';
            $i = 0;
            foreach( $event[ 'datetimes' ] as $datetime ) {
                $html .= '<li style="list-style:none">' . date( 'F j, Y', strtotime( $event[ 'datetimes' ][ $i ][ 'DTT_EVT_start' ] ) ).'</li>';
                $i++;
            }
            $html .= '</ul>';
            $html .= '<p>' . wp_trim_words($event[ 'EVT_desc' ]['rendered'], 55) . '&nbsp;<a href="' . $event[ 'link' ] . '">Learn more</a></p>';
            $html .= '</div><hr />';
        }
    }
    $html .= '</div>';
    return $html;
}
add_shortcode( 'other_site_event_list', 'my_rest_api_event_list' );