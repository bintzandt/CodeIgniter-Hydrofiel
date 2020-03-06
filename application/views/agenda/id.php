<?php
/**
 * @var $event Event
 */
?>
<div class="container-fluid">
	<h3 align="center"><b><?= $event->naam ?></b></h3>
	<p><?= $event->omschrijving ?></p>
	<div class="col-sm-6" style="padding-left: 0">
		<?php if ( isset( $inschrijvingen ) && $event->inschrijfsysteem ) { ?>
			<h4><?= lang( 'agenda_recent' ) ?></h4>
			<table style="width: 100%">
				<?php $i = 0;
				foreach ( $inschrijvingen as $inschrijving ) {
					$i ++;
					if ( $i <= 5 ) { ?>
						<tr>
							<td><?= $inschrijving->naam ?></td>
							<?php if ( $inschrijving->opmerking !== "" ) { ?>
								<td><?= $inschrijving->opmerking ?></td> <?php } ?>
						</tr>
					<?php } else { ?>
						<tr class="inschrijving hidden">
							<td><?= $inschrijving->naam ?></td>
							<?php if ( $inschrijving->opmerking !== "" ) { ?>
								<td><?= $inschrijving->opmerking ?></td> <?php } ?>
						</tr>
					<?php }
				} ?>
			</table>
			<?php if ( $i > 6 ) { ?>
				<a class="show_all" onclick="showAll()"><?= lang( 'agenda_show_registrations' ) ?></a>
				<a class="hide_all hidden" onclick="hideAll()"><?= lang( 'agenda_hide_registrations' ) ?></a>
			<?php } ?>
		<?php } else { ?>
			<br><br>
			<table style="width: 100%">
				<tr>
					<td><?= $event->inschrijfsysteem ? lang( 'agenda_no_registrations' ) : lang( 'agenda_no_registrations_needed' ) ?></td>
				</tr>
			</table>
		<?php } ?>
	</div>
	<div class="col-sm-6" style="padding-left: 0">
		<h4>Details</h4>
		<table style="width:100%;">
			<tr>
				<td><b><?= lang( 'agenda_from' ) ?></b></td>
				<td><?= $event->get_formatted_date_string( 'van' ) ?></td>
			</tr>
			<tr>
				<td><b><?= lang( 'agenda_until' ) ?></b></td>
				<td><?= $event->get_formatted_date_string( 'tot' ) ?></td>
			</tr>
			<tr>
				<td><b><?= lang( 'agenda_location' ); ?></b></td>
				<td><?= $event->locatie ?></td>
			</tr>
			<?php if ( $event->inschrijfsysteem ) { ?>
				<tr>
					<td><b><?= lang( 'agenda_registration_deadline' ) ?></b></td>
					<td><?= $event->get_formatted_date_string( 'inschrijfdeadline' ) ?></td>
				</tr>
				<tr>
					<td><b><?= lang( 'agenda_cancelation_deadline' ) ?></b></td>
					<td><?= $event->get_formatted_date_string( 'afmelddeadline' ) ?></td>
				</tr>
				<tr>
					<td><b><?= lang( 'agenda_nr_maximum' ) ?></b></td>
					<td><?= $event->nr_of_registrations_string() ?></td>
				</tr>
			<?php } ?>
		</table>
	</div>
	<div class="col-sm-12 no_padding margin_10_top">
		<?php if ( $event->inschrijfsysteem ) {
			include 'registration_form.php';
			if ( $registration_details ) { ?>
				<a type="button" class="btn btn-warning form-control"
				   href="/agenda/edit_details/<?= $event->event_id ?>"><?= lang( 'agenda_change_registration' ) ?></a>
			<?php }
		} ?>
		<a href="<?= $ical ?>"><?= lang( 'agenda_add_to_calendar' ) ?></a>
	</div>
</div>
<script>
	function submitForm() {
		$( "#aanmelden" ).submit();
	}
</script>
