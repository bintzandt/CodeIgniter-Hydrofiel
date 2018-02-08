<!DOCTYPE HTML>
<body>
<table cellspacing="0" cellpadding="0" width="100%" style="max-width: 800px" align="center">
    <tr  style="background: #ffab3a; height: 120px; padding: 7px;">
        <td><img src="<?=site_url('/images/logomail.png')?>" alt="Logo" height="100" style="float:left;"></td>
    </tr>
    <tr>
        <td  valign="top" style="text-align: left;">
            Beste secretaris,<br>
            <br>
            <b><?= $naam ?></b> heeft de volgende gegevens aangepast:<br>
            <?php foreach ($change as $key=>$value){ ?>
                &nbsp;&nbsp;&nbsp;&nbsp;<b><?= ucfirst($key)?></b>:&nbsp;<?= $value ?> <br>
            <?php } ?>
            <br>
            Het is wellicht handig om deze wijzigingen ook in Conscribo door te voeren.<br>
            <br>
            Met vriendelijke groet,<br>
            <br>
            Het Hydrofiel-ledensysteem<br>
            <br>
        </td>
    </tr>
    <tr>
        <td style="color: #FFFFFF; background: #315265; padding: 7px;" height="50"></td>
    </tr>
</table>
</body>
