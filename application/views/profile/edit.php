<h3><?= lang( 'profile_title' ) ?><?= $profile->naam ?></h3>
<?= form_open( "/profile/save/" . $profile->id, [ "class" => "form" ] ); ?>
<div class="form-group">
	<div class="col-sm-2">
		<label class="control-label" for="naam"><?= lang( "profile_name" ) ?></label>
	</div>
	<div class="col-sm-10">
		<input id="naam" type="text" class="form-control" disabled value="<?= $profile->naam ?>">
		<input name="naam" type="hidden" value="<?= $profile->naam ?>">
	</div>
</div>
<div class="form-group">
	<div class="col-sm-2">
		<label class="control-label" for="wachtwoord"><?= lang( "profile_password" ) ?></label>
	</div>
	<div class="col-sm-10">
		<input id="wachtwoord" name="wachtwoord1" type="password" class="form-control" value="wachtwoord">
		<input id="wachtwoord2" name="wachtwoord2" type="password" class="form-control" value="wachtwoord">
		<span class="help-block"><?= lang( "profile_password_help" ) ?></span>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-2">
		<label class="control-label" for="email"><?= lang( "profile_email" ) ?></label>
	</div>
	<div class="col-sm-10">
		<input id="email" type="text" name="email" value="<?= $profile->email ?>" class="form-control">
	</div>
</div>
<div class="form-group">
	<div class="col-sm-2">
		<label class="control-label"><?= lang( "profile_visible" ) ?></label>
	</div>
	<div class="col-sm-10">
		<input type="checkbox" name="zichtbaar_email"
		       value="1" <?= ( $profile->zichtbaar_email ) ? 'checked' : '' ?>> <?= lang( "profile_show_email" ) ?>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-2">
		<label class="control-label"><?= lang( "profile_newsletter" ) ?></label>
	</div>
	<div class="col-sm-10">
		<input type="checkbox" name="nieuwsbrief"
		       value="1" <?= ( $profile->nieuwsbrief ) ? 'checked' : '' ?>> <?= lang( "profile_newsletter_msg" ) ?>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-2">
		<label class="control-label">English</label>
	</div>
	<div class="col-sm-10">
		<input type="checkbox" name="engels" value="1" <?= ( $profile->engels ) ? 'checked' : '' ?>> I want to receive
		content in English
	</div>
</div>
<div class="form-group">
	<div class="col-sm-10">
		<input type="submit" class="btn btn-primary" value="<?= lang( 'profile_save' ) ?>">
		<input type="reset" class="btn btn-warning" onclick="window.location.replace(document.referrer)"
		       value="<?= lang( 'profile_cancel' ) ?>">
	</div>
</div>
<?= form_close(); ?>