<?php if (isset($success)) { ?>
    <div class="alert alert-success">
        <strong><?= $success ?></strong>
    </div>
<?php } if (isset($fail)) { ?>
    <div class="alert alert-danger">
        <strong><?= $fail?></strong>
    </div>
<?php } ?>
<h1>Profiel van <?=$profile->naam?><?= ($profile->id===$this->session->id || $superuser) ? ' <a href="/profile/edit/'.$profile->id.'"><span class="fa fa-pencil-square"></span></a>' : ''?><?=$superuser?' <a href="/profile/delete/'.$profile->id.'"><span class="fa fa-trash"></span></a>':''?></h1>
<div class="table-responsive">
    <table class="table table-condensed table-responsive table-user-information">
        <tbody>
        <tr>
            <td>
                <strong>
                    <span class="glyphicon glyphicon-user  text-primary"></span>
                    Naam
                </strong>
            </td>
            <td class="text-primary">
                <?=$profile->naam?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                    <span class="glyphicon glyphicon-gift  text-primary"></span>
                    Geboortedatum
                </strong>
            </td>
            <td class="text-primary">
                <?= date_format(date_create($profile->geboortedatum), 'd-m-Y')?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                    <span class="glyphicon glyphicon-phone text-primary"></span>
                    Mobielnummer
                </strong>
            </td>
            <td class="text-primary">
                <?= $profile->mobielnummer ?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                    <span class="glyphicon glyphicon-envelope text-primary"></span>
                    Email
                </strong>
            </td>
            <td class="text-primary">
                <?= $profile->email ?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                    <span class="glyphicon glyphicon-home text-primary"></span>
                    Adres
                </strong>
            </td>
            <td class="text-primary">
                <?= $profile->adres ?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                    <span class="glyphicon glyphicon-tint text-primary"></span>
                    Lidmaatschap
                </strong>
            </td>
            <td class="text-primary">
                <?= $profile->lidmaatschap ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>