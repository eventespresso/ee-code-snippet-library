<?php
defined('ABSPATH') || exit;

/**
 * sold_out_event_email_notification
 * This function hooks into an action that is triggered
 * when an event's status is toggled to "Sold Out"
 * this method  then queries the database to get info regarding the number of approved registrations
 * and registration limits for each event's datetimes.
 * If any datetime appears to be sold out, then a heavily filtered email is sent out.
 *
 * @author Brent Christensen
 * @param \EE_Event $event
 */
function bc_sold_out_event_email_notification(\EE_Event $event)
{
    global $wpdb;
    $datetimes = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT
                  d.DTT_ID AS Datetime_ID,
                  d.DTT_name AS Datetime_Name,
                  d.DTT_reg_limit AS Datetime_Reg_Limit,
                  count(r.REG_ID) AS Approved_Registrations_Count                  
                FROM {$wpdb->prefix}esp_datetime AS d
                   
                  JOIN {$wpdb->prefix}esp_datetime_ticket AS dt
                    ON dt.DTT_ID = d.DTT_ID              

                  JOIN {$wpdb->prefix}esp_ticket AS t
                    ON t.TKT_ID = dt.TKT_ID          
                                 
                  JOIN {$wpdb->prefix}esp_registration AS r
                    ON r.TKT_ID = t.TKT_ID
                       AND r.STS_ID = 'RAP'
                       
                WHERE  d.DTT_EVT_end > NOW()
                  AND d.DTT_reg_limit != -1                    
                  AND r.EVT_ID = %d                    
                GROUP BY d.DTT_ID
                ORDER BY d.DTT_ID ASC",
            $event->ID()
        )
    );
    $datetime_reg_count_info = '
<table style="border-collapse: collapse;">
    <thead>
        <tr style="border-top:1px solid #eeeeee; border-left:1px solid #eeeeee;">
            <td style="padding:3px 6px; font-weight: bold; border-right:1px solid #eeeeee;">'
                               . __('Datetime ID', 'event_espresso')
                               . '</td>
            <td style="padding:3px 6px; font-weight: bold; border-right:1px solid #eeeeee;">'
                               . __('Datetime Name', 'event_espresso')
                               . '</td>
            <td style="padding:3px 6px; font-weight: bold; border-right:1px solid #eeeeee;">'
                               . __('Datetime Reg_Limit', 'event_espresso')
                               . '</td>
            <td style="padding:3px 6px; font-weight: bold; border-right:1px solid #eeeeee;">'
                               . __('Approved Registrations', 'event_espresso')
                               . '</td>
       </tr>
    </thead>
    <tbody>
        <tr style="border-top:1px solid #eeeeee; border-left:1px solid #eeeeee;">
    ';
    foreach ($datetimes as $datetime) {
        if (
            isset($datetime->Datetime_Reg_Limit, $datetime->Approved_Registrations_Count)
            && $datetime->Datetime_Reg_Limit <= $datetime->Approved_Registrations_Count
        ) {
            $datetime_reg_count_info .= "
            <td style=\"padding:3px 6px; text-align: center; border-right:1px solid #eeeeee; border-bottom:1px solid #eeeeee;\">{$datetime->Datetime_ID}</td>
            <td style=\"padding:3px 6px; border-right:1px solid #eeeeee; border-bottom:1px solid #eeeeee;\">{$datetime->Datetime_Name}</td>
            <td style=\"padding:3px 6px; text-align: center; border-right:1px solid #eeeeee; border-bottom:1px solid #eeeeee;\">{$datetime->Datetime_Reg_Limit}</td>
            <td style=\"padding:3px 6px; text-align: center; border-right:1px solid #eeeeee; border-bottom:1px solid #eeeeee;\">{$datetime->Approved_Registrations_Count}</td>";
        }
    }
    $datetime_reg_count_info .= "
    </tbody>
</table>
";
    $subject_title = apply_filters(
        'AFEE__EES_Espresso_Thank_You__check_for_sold_out_events__sold_out_event_email_subject',
        esc_html__('Sold Out Event Notification', 'event_espresso')
    );
    $msg = apply_filters(
        'AFEE__EES_Espresso_Thank_You__check_for_sold_out_events__sold_out_event_email_message',
        sprintf(
            esc_html__(
                '%1$s%2$sThe event status for "%4$s" (ID:%5$d) has been toggled to "Sold Out".%6$sHere are the Approved Registration Counts for each Datetime:%3$s%7$s',
                'event_espresso'
            ),
            "<h2>{$subject_title}</h2>",
            '<p>',
            '</p>',
            $event->name(),
            $event->ID(),
            '<br />',
            $datetime_reg_count_info
        ),
        $event,
        $datetimes
    );
    wp_mail(
        apply_filters(
            'AFEE__EES_Espresso_Thank_You__check_for_sold_out_events__sold_out_event_email_recipient',
            EE_Config::instance()->organization->email
        ),
        $subject_title,
        $msg
    );
}
add_action('AHEE__EE_Event__set_status__to_sold_out', 'bc_sold_out_event_email_notification');



// End of file bc_sold_out_event_email_notification.php