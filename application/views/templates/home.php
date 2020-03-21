<?php
/**
 * @var $events Event[]
 * @var $birthdays array
 */
?>
<div class="row">
	<div class="col-sm-2 vcenter hidden-xs">
		<a href="https://www.sponsorkliks.com/winkels.php?club=8634" target="_blank" rel="noopener"><img
					class="img-rounded" style="margin: 0 auto;" src="/images/sponsorkliks.gif"
					alt="SponsorKliks, gratis sponsoren!" title="SponsorKliks, sponsor jouw sponsordoel gratis!"
					Border="0"></a>
	</div><!--
    -->
	<div class="col-sm-10 vcenter">
		<p>
			<?= lang( 'home_welcome' ); ?>
		</p>
		<p>
			<?= lang( 'home_sponsor' ); ?>
		</p>
	</div>
</div>
<hr>
<div class="container-fluid" align="left">
	<div class="col-sm-6 homepage_block no_padding padding_left">
		<h3 class="oranje_tekst no_padding"><?= lang( 'home_events' ) ?></h3>
		<?php if ( ! empty( $events ) ) {
			foreach ( $events as $event ) { ?>
				<div class="no_padding">
					<span class="far fa-calendar"></span><a
							href="/agenda/id/<?= $event->event_id ?>"> <?= $event->naam ?></a><br>
					<div style="padding-left: 1em"><?= $event->get_formatted_date_string( 'van' ) ?></div>
				</div>
			<?php }
		}
		else { ?>
			<span class="fa fa-frown-open"></span> <?= lang( 'home_no_events' ) ?>
		<?php } ?>
	</div>
	<div class="col-sm-6 homepage_block no_padding padding_left">
		<h3 class="oranje_tekst no_padding"><?= lang( 'home_birthdays' ) ?></h3>
		<?php if ( is_logged_in() ) : ?>
			<?php foreach ( $birthdays as $birthday ) { ?>
				<div class="no_padding">
					<span class="fa fa-birthday-cake"></span><a
							href="/profile/id/<?= $birthday->id ?>"> <?= $birthday->naam ?>
						(<?= date( 'Y' ) - $birthday->geboortejaar ?>)</a><br>
					<div style="padding-left: 1em"><?= $birthday->geboortedatum ?></div>
				</div>
			<?php } ?>
		<?php else: ?>
			<span class="fa fa-birthday-cake"></span> <?= lang( 'home_login' ); ?>
		<?php endif; ?>
	</div>
</div>
<hr>
<h3 class="oranje_tekst" style="padding-left: 15px"><?= lang( 'home_news' ) ?></h3>
<?php if ( isset( $posts ) ) {
	foreach ( $posts as $post ) { ?>
		<?php if ( $post->post_image !== "" ) { ?>
			<div class='container container-item'>
				<div class='col-md-3'>
					<strong><?= is_english() ? $post->post_title_en : $post->post_title_nl ?></strong>
					<img class="img-responsive no_margin" src="<?= $post->post_image ?>">
				</div>
				<div class='col-md-9' align='left'>
					<p class="news_content">
						<?= is_english() ? $post->post_text_en : $post->post_text_nl ?>
					</p>
				</div>
			</div>
		<?php } else { ?>
			<div class='container container-item'>
				<div class="col-md-12" align="left">
					<?= is_english() ? $post->post_text_en : $post->post_text_nl ?>
				</div>
			</div>
		<?php } ?>
		<hr>
	<?php }
} ?>
