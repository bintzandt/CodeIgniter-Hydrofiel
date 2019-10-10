<div style="text-align:right; vertical-align: top; padding: 20px;"><a href="/beheer/agenda/add"><b>Activiteit
			toevoegen</b></a></div>
<?php if( empty( $events ) ) { ?>
	<b>Er zijn geen evenementen.</b>
<?php } else { ?>
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
<?php }
	if ( empty( $old_events ) ) { ?>
		<b>Er zijn geen oude evenementen.</b>
<?php } else { ?>
		<h3>Oude evenementen</h3>
		<table class="table table-responsive table-striped">
			<thead>
			<tr>
				<th>Naam</th>
				<th>Datum</th>
				<th>Beheer</th>
			</tr>
			</thead>
			<tbody id="oude_evenementen">
			<?php $i = 0; foreach( $old_events as $event ) {
					if ( $i < 5 ) { ?>
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
					<?php } else { ?>
						<tr class="hidden">
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
			<?php $i += 1; } ?>
			</tbody>
		</table>
		<a onclick="showMore()">Laat meer oude evenementen zien</a>
<?php } ?>
<script>
	function showMore(){
		let elements = $('#oude_evenementen tr').filter( function() {
				return $(this)[0].className === "hidden";
		} );
		for ( let i = 0; i < 5; i++ ){
			$( elements[ i ] ).toggleClass( "hidden" );
		}
	}

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
