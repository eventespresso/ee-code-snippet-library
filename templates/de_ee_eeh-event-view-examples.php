<?php
/**
 * Template Name: EE Testing Template
 *
 * This template is just for testing various EEH_Event_View methods.  It is based on the Wordpress
 * Twenty Eleven theme as a page template.
 *
 * NOTE: You will need to change the
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

EE_Registry::instance()->load_helper( 'Event_View' );
$event_id = 321;

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<article>
					<?php $event = EEM_Event::instance()->get_one_by_ID( $event_id ); ?>
					<h2><?php echo $event->name(); ?></h2>
					<div class="entry-content">
						<h3>Display Ticket Selector:</h3>
						<p>
							<?php echo EEH_Event_View::display_ticket_selector( $event_id ); ?>
						</p>
					</div>

					<div class="entry-content">
						<h3>Event Status</h3>
						<p>
							<?php echo EEH_Event_View::event_status( $event_id ); ?>
						</p>
					</div>

					<div class="entry-content">
						<h3>Event Tickets Available</h3>
						<p>
							<?php
								$tickets_available = EEH_Event_View::event_tickets_available( $event_id );
								foreach( $tickets_available as $ticket ) {
									echo $ticket->name() . '<br>';
								}
							?>
						</p>
					</div>

					<div class="entry-content">
						<h3>The Event Date</h3>
						<p>
							<?php echo EEH_Event_View::the_event_date( '', '', $event_id ); ?>
						</p>
					</div>

					<div class="entry-content">
						<h3>The Event End Date</h3>
						<p>
							<?php echo EEH_Event_View::the_event_end_date( '', '', $event_id ); ?>
						</p>
					</div>

					<div class="entry-content">
						<h3>The Earliest Event Date</h3>
						<p>
							<?php echo EEH_Event_View::the_earliest_event_date('', '', $event_id ); ?>
						</p>
					</div>

					<div class="entry-content">
						<h3>The Latest Event Date</h3>
						<p>
							<?php echo EEH_Event_View::the_latest_event_date('','',$event_id); ?>
						</p>
					</div>

					<div class="entry-content">
						<h3>Event Date as Calendar Page</h3>
						<p>
							<?php echo EEH_Event_View::event_date_as_calendar_page($event_id); ?>
						</p>
					</div>

					<div class="entry-content">
						<h3>Primary Date Object</h3>
						<p>
							<?php
								$dtt = EEH_Event_View::get_primary_date_obj( $event_id );
								if ( $dtt instanceof EE_Datetime ) {
									echo $dtt->get_dtt_display_name( true );
									echo '<br>' . $dtt->get_dtt_display_name();
								} else {
									echo 'No Date object found';
								}
							?>
						</p>
					</div>

					<div class="entry-content">
						<h3>Last Date Object</h3>
						<p>
							<?php
								$dtt = EEH_Event_View::get_last_date_obj( $event_id );
								if ( $dtt instanceof EE_Datetime ) {
									echo $dtt->get_dtt_display_name( true );
									echo '<br>' . $dtt->get_dtt_display_name();
								} else {
									echo 'No Date object found';
								}
							?>
						</p>
					</div>

					<div class="entry-content">
						<h3>Earliest Date Object</h3>
						<p>
							<?php
								$dtt = EEH_Event_View::get_earliest_date_obj( $event_id );
								if ( $dtt instanceof EE_Datetime ) {
									echo $dtt->get_dtt_display_name( true );
									echo '<br>' . $dtt->get_dtt_display_name();
								} else {
									echo 'No Date object found';
								}
							?>
						</p>
					</div>

					<div class="entry-content">
						<h3>Latest Date Object</h3>
						<p>
							<?php
								$dtt = EEH_Event_View::get_latest_date_obj( $event_id );
								if ( $dtt instanceof EE_Datetime ) {
									echo $dtt->get_dtt_display_name( true );
									echo '<br>' . $dtt->get_dtt_display_name();
								} else {
									echo 'No Date object found';
								}
							?>
						</p>
					</div>

					<div class="entry-content">
						<h3>All Event Date Objects</h3>
						<h4>Include Expired</h4>
						<p>
							<?php
								$dtts = EEH_Event_View::get_all_date_obj( $event_id, true );
								foreach ( $dtts as $dtt ) {
									if ( $dtt instanceof EE_Datetime ) {
										echo '<br><br>';
										echo $dtt->get_dtt_display_name( true );
										echo '<br>' . $dtt->get_dtt_display_name();
									} else {
										echo 'No Date object found';
									}
								}
							?>
						</p>
						<br>
						<br>
						<h4>No Expired</h4>
						<p>
							<?php
								$dtts = EEH_Event_View::get_all_date_obj( $event_id, false );
								foreach ( $dtts as $dtt ) {
									if ( $dtt instanceof EE_Datetime ) {
										echo '<br><br>';
										echo $dtt->get_dtt_display_name( true );
										echo '<br>' . $dtt->get_dtt_display_name();
									} else {
										echo 'No Date object found';
									}
								}
							?>
						</p>
						<br><br>
						<h4>Include Deleted</h4>
						<p>
							<?php
								$dtts = EEH_Event_View::get_all_date_obj( $event_id, null, true );
								foreach ( $dtts as $dtt ) {
									if ( $dtt instanceof EE_Datetime ) {
										echo '<br><br>';
										echo $dtt->get_dtt_display_name( true );
										echo '<br>' . $dtt->get_dtt_display_name();
									} else {
										echo 'No Date object found';
									}
								}
							?>
						</p>
					</div>

				</article>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>
