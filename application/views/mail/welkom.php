<!DOCTYPE HTML>
<body>
<div style="width: 100%; margin: auto;" align="center">
    <table cellspacing="0" cellpadding="0" width="100%">
        <tr style="background: #ffab3a; height: 120px; padding: 7px;">
            <td><img src="<?=site_url('/images/logomail.png')?>" alt="Logo" height="100" style="float:left;"></td>
        </tr><tr>
            <td  valign="top" style="text-align: left;">
                <p>
                    Lieve <?= $voornaam ?>,<br>
                    <br>
                    Ik heb zojuist je inschrijving verwerkt en je bent vanaf vandaag officieel lid! Dit betekent dat je toegang hebt tot de Hydrofiel website. Met <a href="<?= site_url('/inloggen/reset/'.$recovery)?>">deze</a> link kun je een account aanmaken op <a href="<?=site_url('')?>">www.hydrofiel.nl</a>. Op de website zie je onder andere de agenda en kan je jezelf inschrijven voor activiteiten/toernooien/wedstrijden. Tevens vind je daar het HR en de statuten en andere nuttige informatie.<br>
                    <br>
                    Ook krijg je voortaan de nieuwsbrief, andere belangrijke mails en blijf je op de hoogte van alle gebeurtenissen!<br>
                    <br>
                    De zwemmers trainen op:<br>
                    <ul>
                        <li>Dinsdag: 19.00-20.00 uur en 20.00-21.00 uur</li>
                        <li>Donderdag: 21.30-22.30 uur</li>
                    </ul>
                    <br>
                    De waterpoloërs trainen op:<br>
                    <ul>
                        <li>Dinsdag: 21.00-22.30 uur</li>
                        <li>Donderdag: 20.00-21.30 uur</li>
                    </ul>
                    <br>
                    <b>Aankomende evenementen:</b><br>
                    <ul>
                        <?php foreach ($events as $event) { ?>
                            <li><a href="<?= site_url('/agenda/id/' . $event->event_id )?>"><?= $event->naam?> op <?= date_format(date_create($event->van), 'm-d-Y')?></a></li>
                        <?php } ?>
                    </ul>
                    In de bijlage vind je onze welkomstbrief. Mocht je nog vragen hebben, dan hoor ik deze graag. Dit kan persoonlijk of via een mailtje naar <a href="mailto:secretaris@hydrofiel.nl">secretaris@hydrofiel.nl</a>.<br>
                    <br>
                    Vergeet ons vooral niet te liken op Facebook: <a href="https://www.facebook.com/Hydrofiel/">https://www.facebook.com/Hydrofiel/</a><br>
                    <br>
                    Tot op het bad!<br>
                    <br>
                    Met Harde en Natte groet,<br>
                    <br>
                    Lisa Kersten<br>
                    <i>Secretaris 2017-2018<br>
                        N.S.Z.&W.V. Hydrofiel</i>
                </p>
            </td>
        </tr><tr>
            <td style="color: #FFFFFF; background: #315265; padding: 7px;" height="50"></td>
        </tr>
    </table>
</div>
</body>
</html>
