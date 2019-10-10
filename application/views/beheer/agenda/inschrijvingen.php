<div style="text-align:right; vertical-align: top; padding: 20px;"><a href="/beheer/agenda"><b>Terug</b></a></div>
<?php if( isset( $error ) ) { ?>
	<b>Er zijn geen inschrijvingen voor dit evenement of voor dit evenement kan niet worden ingeschreven.</b>
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
		<?php foreach( $inschrijvingen as $inschrijving ) { ?>
			<tr>
				<td class="clickable-row"
				    data-href="/beheer/agenda/inschrijvingen/<?= $event_id ?>/<?= $inschrijving->member_id ?>"><?= $inschrijving->naam ?></td>
				<td class="clickable-row"
				    data-href="/beheer/agenda/inschrijvingen/<?= $event_id ?>/<?= $inschrijving->member_id ?>"><?= date_format( date_create( $inschrijving->datum ), 'd-m-Y H:i' ) ?></td>
				<td>
					<a onclick="showModal('<?= $inschrijving->naam ?>',<?= $event_id ?>, <?= $inschrijving->member_id ?>)"><span
								class="fa fa-trash"></span></a>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
<?php } ?>
<script>
	function showModal( naam, event, member ) {
		showBSModal( {
			title: "Weet je het zeker?",
			body: "De inschrijving van " + naam + " zal verwijderd worden! ",
			actions: [ {
				label: "Ja",
				cssClass: "btn-danger",
				onClick: function( e ) {
					window.location.replace( "/agenda/afmelden/" + event + "/" + member );
				},
			}, {
				label: "Nee",
				cssClass: "btn-success",
				onClick: function( e ) {
					$( e.target ).parents( ".modal" ).modal( "hide" );
				},
			} ],
		} );
	}
</script>
