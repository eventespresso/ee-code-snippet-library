<?php
/**
 * When a registration is checked into an event in Event Espresso, tag the corresponding contact in Infusionsoft
 * with the specified tags.
 * If you want help finding the ID of a tag, here is a video showing how to find them
 * https://drive.google.com/file/d/1Wg2yuGsrGg5yErIluFaiMkeWyvSTkHpL/view.
 */

// tag IDs to assign to contact when they're checked in
$is_checkin_tags = array(
    // replace this list of tag IDs with whatever tag IDs you want
    180,
    314,
    312
);

////////////////////////////////////////////////////////////////
// that's it! stop tweaking

// also sync checkins
add_filter('FHEE__EED_Infusionsoft__models_synced_to_IS', 'sync_checkins');
function sync_checkins($models_to_sync_to_IS)
{
    $models_to_sync_to_IS[] = 'Checkin';
    return $models_to_sync_to_IS;
}

add_filter('FHEE__EE_Checkin__sync_to_infusionsoft', 'sync_checkin_to_IS',10,3);
/**
 * When a registration is checked into an event, tags them with the tags specified at the start of this function
 * @param $return_value
 * @param $model_obj
 * @param $args
 * @return bool
 * @throws EE_Error
 * @throws InvalidArgumentException
 * @throws ReflectionException
 * @throws \EventEspresso\core\exceptions\InvalidDataTypeException
 * @throws \EventEspresso\core\exceptions\InvalidInterfaceException
 */
function sync_checkin_to_IS($return_value, $model_obj, $args)
{
    global $is_checkin_tags;
    $tags = $is_checkin_tags;
    // verify Infusionsoft integration is on
    if( ! class_exists('EED_Infusionsoft')) {
        return $return_value;
    }
    // don't sync it twice
    if (EED_Infusionsoft::synced_on_this_request($model_obj)) {
        return false;
    }
    // check we're dealing with a valid EE_Checkin
    if( ! $model_obj instanceof EE_Checkin) {
        return $return_value;
    }
    // we only care about check INs. So verify that before doing any DB queries
    if( ! $model_obj->status()) {
        return $return_value;
    }
    $reg = $model_obj->get_first_related('Registration');
    if( ! $reg instanceof EE_Registration) {
        return $return_value;
    }
    $attendee = $reg->attendee();
    if( ! $attendee instanceof EE_Attendee) {
        return $return_value;
    }
    // ensure registration is already sync'd
    $reg->sync_to_infusionsoft();
    // get IS contact ID
    $IS_contact_id = $attendee->get_extra_meta(EEE_Infusionsoft_Attendee::extra_meta_IS_contact_ID, true);
    if( ! $IS_contact_id) {
        return false;
    }
    $tags = $attendee->determine_groups_to_sync($tags);
    $isdk = EED_Infusionsoft::infusionsoft_connection();
    foreach($tags as $tag) {
        // tag em and bag em
        $success = $isdk->grpAssign($IS_contact_id, $tag);
        // how did we do?
        if (EED_Infusionsoft::is_IS_error($success)) {
            EE_Log::instance()->log(
                __FILE__,
                __FUNCTION__,
                sprintf(
                    esc_html__(
                    // @codingStandardsIgnoreStart
                        'Could not assign Infusionsoft Contact %1$s for EE registration %2$s to tag %3$s while they were checked in. Infusionsoft Error was %4$s',
                        // @codingStandardsIgnoreEnd
                        'event_espresso'
                    ),
                    $IS_contact_id,
                    $reg->ID(),
                    $tag,
                    $success
                ),
                'infusionsoft'
            );
        } else {
            $attendee->save_group_synced($tag);
        }
    }
}