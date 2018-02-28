<div>
    <p> Hydrofiel is de Nijmeegse Studenten Zwem- en Waterpolovereniging die haar leden de mogelijkheid geeft om op een sportieve en vooral gezellige manier de zwem- of waterpolosport te beoefenen.
        Ben je een beginnende of juist een ervaren zwemmer of waterpoloer?
        In beide gevallen ben je bij ons op de juiste plek.
        Onze trainingen zijn toegankelijk voor elk niveau.
        Kom eens een keertje meetrainen om te kijken of Hydrofiel en haar leden bij jou passen! Iedereen is meer dan welkom!<br><br>
        Naast de trainingen die twee keer per week plaatsvinden en de wekelijkse borrel op donderdagavond in Cafe de Kroon zijn er maandelijks activiteiten die uiteenlopen van toernooien, wedstrijden tot borrels, een cantus, een barbeque, lasergamen, diner rouler en nog veel meer.
    </p>
</div>
<hr>
<div class="container-fluid" align="left">
    <div class="col-sm-6 homepage_block">
        <h3 class="oranje_tekst">Evenementen</h3>
            <?php if(!empty($events)) { foreach ($events as $event) { ?>
                <div>
                    <span class="fa fa-calendar-o"></span><a href="/agenda/id/<?=$event->event_id?>"> <?= $event->naam ?></a><br><div style="padding-left: 1em"><?= date_format(date_create($event->van), 'd-m-Y')?></div>
                </div>
            <?php } } else { ?>
                <span class="fa fa-frown-o"></span> Er zijn geen aankomende evenementen.
            <?php } ?>
    </div>
    <div class="col-sm-6 homepage_block">
        <h3 class="oranje_tekst">Verjaardagen</h3>
            <?php if ($login) : ?>
                <?php foreach ($verjaardagen as $verjaardag) { ?>
                    <div><span class="fa fa-birthday-cake"></span>
                        <a href="/profile/id/<?= $verjaardag->id?>"><?= $verjaardag->naam?> (<?= date('Y') - $verjaardag->geboortejaar ?>)</a><br><div style="padding-left: 2em"><?= $verjaardag->geboortedatum ?></div>
                    </div>
                <?php } ?>
            <?php else: ?>
                    <span class="fa fa-birthday-cake"></span> Log in om de verjaardagen te zien.
            <?php endif; ?>
    </div>
</div>
<hr>
<h3 class="oranje_tekst" style="padding-left: 15px">Nieuws</h3>
<div><p><?= $tekst ?></p></div>
<hr>
<div id="facebookfeed"></div>