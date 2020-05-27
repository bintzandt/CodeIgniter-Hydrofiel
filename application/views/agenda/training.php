<?php
/**
 * @var Training[] $waterpolo
 * @var Training[] $swimming
 */
?>
<h2>Waterpolo trainingen</h2>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Naam</th>
			<th>Van</th>
			<th>Tot</th>
			<th>Inschrijvingen</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach( $waterpolo as $waterpolo_training ){ ?>
		<tr class="clickable-row" data-href="/agenda/view_training/<?= $waterpolo_training->event_id ?>">
			<td><?= $waterpolo_training->naam ?></td>
			<td><?= $waterpolo_training->van ?></td>
			<td><?= $waterpolo_training->tot ?></td>
			<td><?= $waterpolo_training->nr_of_registrations() . '/' . $waterpolo_training->maximum ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<h2>Zwem trainingen</h2>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Naam</th>
			<th>Van</th>
			<th>Tot</th>
			<th>Inschrijvingen</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach( $swimming as $swimming_training ){ ?>
		<tr class="clickable-row" data-href="/agenda/view_training/<?= $swimming_training->event_id ?>">
			<td><?= $swimming_training->naam ?></td>
			<td><?= $swimming_training->van ?></td>
			<td><?= $swimming_training->tot ?></td>
			<td><?= $swimming_training->nr_of_registrations() . '/' . $swimming_training->maximum ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
