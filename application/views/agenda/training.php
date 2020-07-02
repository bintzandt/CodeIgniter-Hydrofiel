<?php
/**
 * @var Training[] $waterpolo
 * @var Training[] $swimming
 */
?>
<p>Via onderstaande inschrijving kun je je aanmelden voor de trainingen. De inschrijving gaat op <b>zaterdag om 10.00</b> open.</p>
<p>Als je gezondheidsklachten krijgt, <b>schrijf je dan uit</b>. Bij een no-show zonder afmelding, ben je in het vervolg <b>niet meer welkom</b>.</p>
<p>Voor zwemmers is het eerste uur van <i>19.45</i> tot <i>20.45</i> voor de <b>minder snelle</b> zwemmers (PR langzamer dan 1.15 op de 100 vrij). Het tweede uur (<i>20.45-21.45</i>) is voor de <b>snellere</b> zwemmers.</p>
<p>Voor waterpolo geldt dat er van <i>21.45</i> tot <i>22.45</i> getraind kan worden. Er zijn 12 plekken voor vrouwen en 12 voor mannen.</p>
<p>Als het druk wordt, kan het zijn dat we mensen die zich vaak hebben aangemeld vragen om zich voor een paar trainingen uit te schrijven, zodat ook andere leden een kans hebben.</p>
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
			<td><?= $swimming_training->nr_of_registrations() ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
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
			<td><?= $waterpolo_training->nr_of_registrations() ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

