<html>
<?php
function get_status( $bereikbaar, $zichtbaar ) {
	$status = '';
	if( $zichtbaar == 'ja' ) {
		$status .= '<i class="fa fa-check">';
	}
	else {
		$status .= '<i class="fa fa-eye-slash">';
	}
	if( $bereikbaar == 'nee' ) {
		$status .= '<i class="fa fa-exclamation-circle">';
	}

	return $status;
}

?>
<script>
	function showModal( naam, pagina_id ) {
		showBSModal( {
			title: "Weet je het zeker?",
			body: "De pagina '" + naam + "' zal verwijderd worden! ",
			actions: [ {
				label: "Ja",
				cssClass: "btn-danger",
				onClick: function( e ) {
					window.location.replace( "/beheer/pagina/delete/" + pagina_id );
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
<body>
<div align="right" style="padding: 20px"><a href="/beheer/pagina/toevoegen"><b>Pagina Toevoegen</b></a></div>
<table class="table table-condensed table-striped table-hover table-responsive">
	<thead>
	<tr>
		<th>Pagina naam</th>
		<th class="hidden-xs">Status</th>
		<th>Beheer</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach( $hoofdpagina as $hoofd ) { ?>
		<tr>
			<td class="clickable-row" data-href="/beheer/pagina/edit/<?= $hoofd->id ?>"
			    style="padding-right: 0;"><?= $hoofd->naam ?></td>
			<td class="hidden-xs"><?= get_status( $hoofd->bereikbaar, $hoofd->zichtbaar ) ?></td>
			<td><?= form_open( '' ); ?>
				<a href="/beheer/pagina/up/<?= $hoofd->id ?>"><i class="fa fa-arrow-up"></i></a>
				<a href="/beheer/pagina/down/<?= $hoofd->id ?>"><i class="fa fa-arrow-down"></i></a>
				<a href="/beheer/pagina/edit/<?= $hoofd->id ?>"><i class="fa fa-pencil-alt"></i></a>
				<a onclick="showModal('<?= $hoofd->naam ?>', <?= $hoofd->id ?>)" <i class="fa fa-trash"></i></a>
				<?= form_close() ?>
			</td>
		</tr>
		<?php if( $hoofd->subpagina !== NULL ) {
			foreach( $hoofd->subpagina as $sub ) { ?>
				<tr>
					<td class="clickable-row" data-href="/beheer/pagina/edit/<?= $sub->id ?>"
					    style="padding-left: 20px;padding-right: 0;"><?= $sub->naam ?></td>
					<td class="hidden-xs"><?= get_status( $sub->bereikbaar, $sub->zichtbaar ) ?></td>
					<td><?= form_open( '' ); ?>
						<a href="/beheer/pagina/up/<?= $sub->id ?>"><i class="fa fa-arrow-up"></i></a>
						<a href="/beheer/pagina/down/<?= $sub->id ?>"><i class="fa fa-arrow-down"></i></a>
						<a href="/beheer/pagina/edit/<?= $sub->id ?>"><i class="fa fa-pencil-alt"></i></a>
						<a onclick="showModal('<?= $sub->naam ?>', <?= $sub->id ?>)"><i class="fa fa-trash"></i></a>
						<?= form_close(); ?>
					</td>
				</tr>
			<?php }
		}
	} ?>
	</tbody>
</table>
<table>
	<tr>
		<th>Legenda</th>
	</tr>
	<tr>
		<td colspan="4" style="border-bottom: 2px solid #000000;"></td>
	</tr>
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td><i class="fa fa-check"></i></td>
					<td>Pagina zichtbaar in menu</td>
				</tr>
				<tr>
					<td><i class="fa fa-eye-slash"></i></td>
					<td>Pagina niet zichtbaar in menu</td>
				</tr>
				<tr>
					<td><i class="fa fa-exclamation-circle"></i></td>
					<td>Deze pagina kan niet gezien worden</td>
				</tr>
				<tr>
					<td><i class="fa fa-arrow-down"></i></td>
					<td>Pagina omlaag verplaatsen</td>
				</tr>
				<tr>
					<td width="50"><i class="fa fa-arrow-up"></i></td>
					<td>Pagina omhoog verplaatsen</td>
				</tr>
				<tr>
					<td><i class="fa fa-pencil-alt"></i></td>
					<td>Pagina bewerken</td>
				</tr>
				<tr>
					<td><i class="fa fa-trash"></i></td>
					<td>Pagina verwijderen</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
