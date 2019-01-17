<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file
//* usage: place [ee_todays_events] into a shortcode block

function ee_rest_api_todays_events( $atts ) {
    $atts = shortcode_atts( array(
        'url' => home_url()
    ), $atts, 'ee_todays_events' );
    $curdate = date("Y-m-d 00:00:00");
    $enddatec = date('Y-m-d H:i:s', strtotime('tomorrow'));
    if($atts['url'] == '') {
        return;
    }
    $data_url = esc_url($atts['url']) . "/wp-json/ee/v4.8.36/events?calculate=image_medium_large&include=Datetime&where[Datetime.DTT_EVT_start][0]=BETWEEN&where[Datetime.DTT_EVT_start][1][]=" . urlencode($curdate) . "&where[Datetime.DTT_EVT_start][1][]=" . urlencode($enddatec);
    $json = file_get_contents($data_url, true); 
    $events = json_decode($json, true);  
    $html = '<div id="embedded-events" style="max-width: 700px; margin: 0 auto;">';
    if ($events){
        foreach ($events as $event){
            $html .= '<div class="embedded-event">';
            $html .= '<h3><a href="' . $event[ 'link' ] . '">' . $event[ 'EVT_name' ] . '</a></h3>';
            $html .= '<div style="text-align:center">';
            $featured_image_url = $event['_calculated_fields']['image_medium_large']['url'];
            $html .= $featured_image_url ? '<a href="' . esc_url( $event['link'] ). '"><img src="' . esc_url( $featured_image_url ) . '" /></a>' : '';
            $html .= '</div>';
            $html .= '<p>' . wp_trim_words($event[ 'EVT_desc' ]['rendered'], 55) . '&nbsp;<a href="' . $event[ 'link' ] . '">Read more</a></p>';
            $html .= '</div><hr />';
        }
    }
    $html .= '</div>';
    return $html;
}
add_shortcode( 'ee_todays_events', 'ee_rest_api_todays_events' );