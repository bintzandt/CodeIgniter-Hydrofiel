<!DOCTYPE HTML>
<body>
<div style="width: 100%; margin: auto;" align="center">
    <table cellspacing="0" cellpadding="0" width="100%">
        <tr style="background: #ffab3a; height: 120px; padding: 7px;">
            <td><img src="<?= site_url('/images/logomail.png')?>" alt="Logo" height="100" style="float:left;"></td>
        </tr><tr>
            <td  valign="top" style="text-align: left;">
                <p>
                    Beste,<br>
                    <br>
                    Je hebt aangegeven je wachtwoord niet meer te weten. Met <a href="<?=site_url('/inloggen/reset/' . $recovery)?>">deze</a>
                    link kun je je wachtwoord opnieuw instellen.<br>
                    <br>
                    <b>Let op: Deze link vervalt automatisch op <?= date_format(date_create($valid), 'd-m-Y') ?> om <?= date_format(date_create($valid), 'H:i') ?>.</b><br>
                    <br>
                    Mocht je geen nieuw wachtwoord hebben aangevraagd dan hoef je niets te doen. Alleen als je herhaaldelijk
                    deze mail ontvangt kun je contact opnemen met <a href="mailto:webmaster@hydrofiel.nl">webmaster@hydrofiel.nl</a>.<br>
                    <br>
                </p>
            </td>
        </tr><tr>
            <td style="color: #FFFFFF; background: #315265; padding: 7px;" height="50"></td>
        </tr>
    </table>
</div>
</body>
</html>
