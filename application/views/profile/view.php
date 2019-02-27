<?php if( isset( $success ) ) { ?>
	<div class="alert alert-success">
		<strong><?= $success ?></strong>
	</div>
<?php }
if( isset( $fail ) ) { ?>
	<div class="alert alert-danger">
		<strong><?= $fail ?></strong>
	</div>
<?php } ?>
<h3><?= lang( "profile_title" ) ?><?= $profile->naam ?><?= ( $profile->id === $this->session->id || $superuser ) ? ' <a href="/profile/edit/' . $profile->id . '"><span class="fa fa-pencil-alt"></span></a>' : '' ?><?= $superuser ? ' <a href="/beheer/leden/delete/' . $profile->id . '"><span class="fa fa-trash"></span></a>' : '' ?></h3>
<div class="table-responsive">
	<table class="table table-condensed table-responsive table-user-information">
		<tbody>
		<tr>
			<td>
				<strong>
					<span class="glyphicon glyphicon-user  text-primary"></span>
					<?= lang( "profile_name" ); ?>
				</strong>
			</td>
			<td class="text-primary">
				<?= $profile->naam ?>
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					<span class="glyphicon glyphicon-gift  text-primary"></span>
					<?= lang( "profile_birthday" ); ?>
				</strong>
			</td>
			<td class="text-primary">
				<?= date_format( date_create( $profile->geboortedatum ), 'd-m-Y' ) ?>
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					<span class="glyphicon glyphicon-phone text-primary"></span>
					<?= lang( "profile_mobile" ); ?>
				</strong>
			</td>
			<td class="text-primary">
				<?= $profile->mobielnummer ?>
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					<span class="glyphicon glyphicon-envelope text-primary"></span>
					<?= lang( "profile_email" ); ?>
				</strong>
			</td>
			<td class="text-primary">
				<?= $profile->email ?>
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					<span class="glyphicon glyphicon-home text-primary"></span>
					<?= lang( "profile_address" ); ?>
				</strong>
			</td>
			<td class="text-primary">
				<?= $profile->adres ?>
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					<span class="glyphicon glyphicon-tint text-primary"></span>
					<?= lang( "profile_membership" ); ?>
				</strong>
			</td>
			<td class="text-primary">
				<?= $profile->lidmaatschap ?>
			</td>
		</tr>
		</tbody>
	</table>
</div>