<?php
//* Please do NOT include the opening php tag, except of course if you're starting with a blank file

//* Exclude certain events from the events calendar for Event Espresso
function loc_ee_exclude_certain_event_statuses_from_events_calendar( $public_event_stati ) {
            unset( $public_event_stati[ EEM_Event::sold_out ] );
            // unset( $public_event_stati[ EEM_Event::postponed ] );
            // unset( $public_event_stati[ EEM_Event::cancelled ] );
            return $public_event_stati;
        }
add_filter( 'AFEE__EED_Espresso_Calendar__get_calendar_events__public_event_stati', 'loc_ee_exclude_certain_event_statuses_from_events_calendar', 10, 1 );