<div>
    <p>
        <?= lang('home_welcome'); ?>
    </p>
</div>
<hr>
<div class="container-fluid" align="left">
    <div class="col-sm-6 homepage_block no_padding padding_left">
        <h3 class="oranje_tekst no_padding"><?=lang('home_events')?></h3>
            <?php if(!empty($events)) { foreach ($events as $event) { ?>
                <div class="no_padding">
                    <span class="fa fa-calendar-o"></span><a href="/agenda/id/<?=$event->event_id?>"> <?= $engels ? $event->en_naam : $event->nl_naam ?></a><br><div style="padding-left: 1em"><?= date_format(date_create($event->van), 'd-m-Y')?></div>
                </div>
            <?php } } else { ?>
                <span class="fa fa-frown-o"></span> <?= lang('home_no_events') ?>
            <?php } ?>
    </div>
    <div class="col-sm-6 homepage_block no_padding padding_left">
        <h3 class="oranje_tekst no_padding"><?= lang('home_birthdays') ?></h3>
            <?php if ($login) : ?>
                <?php foreach ($verjaardagen as $verjaardag) { ?>
                    <div class="no_padding">
                        <span class="fa fa-birthday-cake"></span><a href="/profile/id/<?= $verjaardag->id?>"> <?= $verjaardag->naam?> (<?= date('Y') - $verjaardag->geboortejaar ?>)</a><br><div style="padding-left: 1em"><?= $verjaardag->geboortedatum ?></div>
                    </div>
                <?php } ?>
            <?php else: ?>
                    <span class="fa fa-birthday-cake"></span> <?= lang('home_login');?>
            <?php endif; ?>
    </div>
</div>
<hr>
<h3 class="oranje_tekst" style="padding-left: 15px"><?= lang('home_news') ?></h3>
<div><p><?= $tekst ?></p></div>
<hr>
<div id="facebookfeed"></div>