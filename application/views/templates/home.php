<script lang="text/javascript" src="/assets/components/home.js"></script>
<div class="row">
    <div class="col-sm-2 vcenter hidden-xs">
        <a href="https://www.sponsorkliks.com/winkels.php?club=8634" target="_blank"><img class="img-rounded" style="margin: 0 auto;" src="/images/sponsorkliks.gif" alt="SponsorKliks, gratis sponsoren!" title="SponsorKliks, sponsor jouw sponsordoel gratis!" Border="0"></a>
    </div><!--
    --><div class="col-sm-10 vcenter">
        <p>
            <?= lang('home_welcome'); ?>
        </p>
        <p>
            <?= lang('home_sponsor'); ?>
        </p>
    </div>
</div>
<hr>
<div class="container-fluid" align="left">
    <div class="col-sm-6 homepage_block no_padding padding_left">
        <h3 class="oranje_tekst no_padding"><?=lang('home_events')?></h3>
            <?php if(!empty($events)) { foreach ($events as $event) { ?>
                <div class="no_padding">
                    <span class="far fa-calendar"></span><a href="/agenda/id/<?=$event->event_id?>"> <?= $engels ? $event->en_naam : $event->nl_naam ?></a><br><div style="padding-left: 1em"><?= date_format(date_create($event->van), 'd-m-Y H:i')?></div>
                </div>
            <?php } } else { ?>
                <span class="fa fa-frown-open"></span> <?= lang('home_no_events') ?>
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
<?php if(isset($posts)) { foreach ($posts as $post) { ?>
    <?php if ($post->post_image !== "") { ?>
        <div class='container container-item'>
            <div class='col-md-3'>
                <strong><?= $engels ? $post->post_title_en : $post->post_title_nl?></strong>
                <img class="img-responsive no_margin" src="<?= $post->post_image?>">
            </div>
            <div class='col-md-9' align='left'>
                <p class="news_content">
                    <?= $engels ? $post->post_text_en : $post->post_text_nl ?>
                </p>
            </div>
        </div>
    <?php } else { ?>
        <div class='container container-item'>
            <div class="col-md-12" align="left">
                <?= $engels ? $post->post_text_en : $post->post_text_nl ?>
            </div>
        </div>
    <?php } ?>
    <hr>
<?php }} ?>
<div id="facebookfeed"></div>