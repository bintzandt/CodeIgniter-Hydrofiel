<div class="row">
    <div class="col-md-2 vcenter d-none d-md-block">
        <a href="https://www.sponsorkliks.com/winkels.php?club=8634" target="_blank" rel="noopener"><img
                    class="rounded" style="margin: 0 auto;" src="/images/sponsorkliks.gif"
                    alt="SponsorKliks, gratis sponsoren!" title="SponsorKliks, sponsor jouw sponsordoel gratis!"
                    Border="0"></a>
    </div><!--
    -->
    <div class="col-md-10 vcenter">
        <p>
            <?= lang('home_welcome'); ?>
        </p>
        <p>
            <?= lang('home_sponsor'); ?>
        </p>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md homepage_block no_padding padding_left">
        <h3 class="oranje_tekst no_padding"><?= lang('home_events') ?></h3>
        <?php if (!empty($events)) {
            foreach ($events as $event) { ?>
                <div class="no_padding">
                    <span class="far fa-calendar"></span><a
                            href="/agenda/id/<?= $event->event_id ?>"> <?= $engels ? $event->en_naam : $event->nl_naam ?></a><br>
                    <div style="padding-left: 1em"><?= date_format(date_create($event->van), 'd-m-Y H:i') ?></div>
                </div>
            <?php }
        } else { ?>
            <span class="fa fa-frown-open"></span> <?= lang('home_no_events') ?>
        <?php } ?>
    </div>
    <div class="col-md homepage_block no_padding padding_left">
        <h3 class="oranje_tekst no_padding"><?= lang('home_birthdays') ?></h3>
        <?php if ($login) : ?>
            <?php foreach ($verjaardagen as $verjaardag) { ?>
                <div class="no_padding">
                    <span class="fa fa-birthday-cake"></span><a
                            href="/profile/id/<?= $verjaardag->id ?>"> <?= $verjaardag->naam ?>
                        (<?= date('Y') - $verjaardag->geboortejaar ?>)</a><br>
                    <div style="padding-left: 1em"><?= $verjaardag->geboortedatum ?></div>
                </div>
            <?php } ?>
        <?php else: ?>
            <span class="fa fa-birthday-cake"></span> <?= lang('home_login'); ?>
        <?php endif; ?>
    </div>
</div>
<?php if(isset($posts) && !empty($posts)){ ?>
<hr>
<div class="row">
    <h3 class="oranje_tekst" style="padding-left: 15px"><?= lang('home_news') ?></h3>
    <?php foreach ($posts as $post) { ?>
            <?php if ($post->post_image !== "") { ?>
                <div class='col-lg'>
                    <strong><?= $engels ? $post->post_title_en : $post->post_title_nl ?></strong>
                    <img class="img-fluid no_margin" src="<?= $post->post_image ?>">
                </div>
                <div class='col-lg-9' align='left'>
                    <p class="news_content">
                        <?= $engels ? $post->post_text_en : $post->post_text_nl ?>
                    </p>
                </div>
            <?php } else { ?>
                <div class="col-lg" align="left">
                    <?= $engels ? $post->post_text_en : $post->post_text_nl ?>
                </div>
            <?php } ?>
            <hr>
        <?php }
    ?>
</div>
<?php } ?>
