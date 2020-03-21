<?php
/**
 * @var $event               Event
 * @var $aangemeld           bool
 * @var $aantal_aanmeldingen int
 */
// Check if the current person is already registered.
if ( $aangemeld ) {
	// If the user can still cancel, we display a cancel button.
	if ( $event->can_cancel() ) { ?>
		<div class="form-group">
			<a
					href="/agenda/afmelden/<?= $event->event_id ?>"
					class="btn btn-primary center-block"
			>
				<?= lang( 'agenda_cancel' ); ?>
			</a>
		</div>
	<?php } else {
		// Display a warning that the registration cannot be cancelled. ?>
		<div class="alert alert-warning">
			<strong><?= lang( 'agenda_no_cancel' ); ?></strong>
		</div>
	<?php } ?>
<?php } else {
	// Display a warning if the registrations are closed.
	if ( ! $event->can_register() ){ ?>
		<div class="alert alert-warning">
			<strong><?= lang( 'agenda_no_registration' ); ?></strong>
		</div>
	<?php }
	// Display a warning if the event is full.
	elseif ( $event->is_full() ){ ?>
		<div class="alert alert-warning">
			<strong><?= lang( 'agenda_full' ); ?></strong>
		</div>
	<?php }
	// Otherwise display the registration form.
	else {
		echo form_open( '/agenda/aanmelden', [ 'id'   => 'aanmelden', 'name' => 'aanmelden' ] ); ?>
		<input type="hidden" value="<?= $event->event_id ?>" name="event_id" />
		<input type="hidden" value="<?= $event->soort ?>" name="event_soort">
		<?php if ( $event->soort === "nszk" ) {
			$slagen = json_decode( $event->slagen );
			foreach ( $slagen as $slag ) { ?>
				<div class="form-group">
					<div class="col-sm-4 no_padding">
						<label><?= $slag ?></label>
					</div>
					<div class="col-sm-8 no_padding">
						<input type="hidden" value="<?= $slag ?>" name="slag[]">
						<input type="text" class="form-control" name="tijd[]" placeholder="Tijd" />
					</div>
				</div>
			<?php }
		} ?>
		<div class="form-group no_padding">
			<input type="text" name="opmerking" maxlength="20" class="form-control" style="margin-top: 20px"
			       placeholder="<?= lang( "agenda_remark" ); ?>">
			<?php if ( $event->betalen ) { ?>
				<input id="agree" type="checkbox" required>
				<label for="agree" style="font-weight: normal"><?= lang( 'agenda_agree_terms' ) ?></label>
			<?php } ?>
		</div>
		<div class="form-group">
			<button type="submit"
			        class="btn btn-primary form-control"><?= lang( 'agenda_register' ) ?></button>
		</div>
	<?php
		echo form_close();
	}
}
