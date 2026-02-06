<?php
/**
 * Plugin Name: Cafe – Event Espresso Ticket Messages
 * Description: Customize Event Espresso front-end messages for closed/ended ticket sales without editing Event Espresso plugin files.
 * Version: 1.0.0
 * Author: Cafe
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Event Espresso builds some ticket selector notices without exposing a dedicated filter for the final message.
 * Using WordPress's `gettext` filter lets us safely override specific Event Espresso strings in an upgrade-safe way.
 *
 * Note: These replacements are intentionally scoped to the `event_espresso` text domain.
 */
add_filter(
    'gettext',
    static function ($translated, $text, $domain) {
        if ($domain !== 'event_espresso') {
            return $translated;
        }

        // Only change front-end strings (admin screens should remain unchanged).
        if (is_admin() && ! wp_doing_ajax()) {
            return $translated;
        }

        switch ($text) {
            /**
             * DisplayTicketSelector::noTicketAvailableMessage()
             *
             * Scenario: Ticket Selector is being shown, but EE found ZERO tickets for the event
             * (ie `count($tickets) < 1` after querying for visible tickets).
             *
             * Common causes:
             * - No tickets were created for the event
             * - All tickets are expired/ended (end date in the past)
             * - Ticket visibility rules filtered everything out
             *
             * Note: For event admins, Event Espresso appends a separate "Note to Event Admin…" block.
             */
            case "We're sorry, but all ticket sales have ended.":
                return "We're sorry — ticket sales for this event are now closed.";

            /**
             * DisplayTicketSelector::expiredEventMessage()
             *
             * Scenario: The EVENT itself is expired (`$event->is_expired()` is true), so registration is over
             * regardless of ticket availability.
             */
            case "We're sorry, but all tickets sales have ended because the event is expired.":
                return "This event has ended and registration is now closed.";

            /**
             * DisplayTicketSelector::ticketSalesClosedMessage()
             *
             * Scenario: Ticket sales were disabled by event configuration, not by ticket inventory.
             * Specifically: the event's "Maximum number of tickets allowed per order" was set to 0, which
             * makes `$this->getMaxAttendees() < 1` and short-circuits the Ticket Selector.
             *
             * Note: For event admins, Event Espresso appends a separate "Note to Event Admin…" block.
             */
            case "We're sorry, but ticket sales have been closed at this time. Please check back again later.":
                return "Registration is currently closed for this event.";

            default:
                return $translated;
        }
    },
    20,
    3
);

