<div style="text-align:right; vertical-align: top; padding: 20px;"><a href="/beheer/agenda/add"><b>Activiteit
			toevoegen</b></a></div>
<?php if( empty( $events ) ) { ?>
	<b>Er zijn geen evenementen.</b>
<?php } else { ?>
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
	<table class="table table-responsive table-striped">
		<thead>
		<tr>
			<th>Naam</th>
			<th>Datum</th>
			<th>Beheer</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach( $events as $event ) { ?>
			<tr>
				<td class="clickable-row"
				    data-href="/beheer/agenda/inschrijvingen/<?= $event->event_id ?>"><?= $event->nl_naam ?></td>
				<td class="clickable-row"
				    data-href="/beheer/agenda/inschrijvingen/<?= $event->event_id ?>"><?= date_format( date_create( $event->van ), 'd-m-Y H:i' ) ?></td>
				<td>
					<a href="/beheer/agenda/edit/<?= $event->event_id ?>"><span class="fa fa-edit"></span></a>
					<a onclick="showModal('<?= $event->nl_naam ?>', <?= $event->event_id ?>)"><span
								class="fa fa-trash"></span></a>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
<?php } ?>
<script>
	function showModal( naam, event_id ) {
		showBSModal( {
			title: "Weet je het zeker?",
			body: "Het evenement '" + naam + "' zal verwijderd worden! ",
			actions: [ {
				label: 'Ja',
				cssClass: 'btn-danger',
				onClick: function( e ) {
					window.location.replace( '/beheer/agenda/delete/' + event_id );
				}
			}, {
				label: 'Nee',
				cssClass: 'btn-success',
				onClick: function( e ) {
					$( e.target ).parents( '.modal' ).modal( 'hide' );
				}
			} ]
		} );
	}
</script>
