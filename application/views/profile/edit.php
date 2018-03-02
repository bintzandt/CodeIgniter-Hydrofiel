<h1>Profiel van <?= $profile->naam?></h1>
<?= form_open("/profile/save/" . $profile->id, array("class" => "form")); ?>
    <div class="form-group">
        <div class="col-sm-2">
            <label class="control-label" for="naam">Naam</label>
        </div>
        <div class="col-sm-10">
            <input id="naam" type="text" class="form-control" disabled value="<?= $profile->naam?>">
            <input name="naam" type="hidden" value="<?= $profile->naam?>">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2">
            <label class="control-label" for="wachtwoord">Wachtwoord</label>
        </div>
        <div class="col-sm-10">
            <input id="wachtwoord" name="wachtwoord1" type="password" class="form-control" value="wachtwoord">
            <input id="wachtwoord2" name="wachtwoord2" type="password" class="form-control" value="wachtwoord">
            <span class="help-block">Laat dit veld leeg als je het wachtwoord niet wilt aanpassen.</span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2">
            <label class="control-label" for="mobiel">Telefoon</label>
        </div>
        <div class="col-sm-10">
            <input id="mobiel" type="text" name="mobielnummer" value="<?= $profile->mobielnummer?>" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2">
            <label class="control-label" for="mobiel">Email</label>
        </div>
        <div class="col-sm-10">
            <input id="email" type="text" name="email" value="<?= $profile->email?>" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2">
            <label class="control-label" for="adres">Adres</label>
        </div>
        <div class="col-sm-10">
            <input id="adres" type="text" name="adres" value="<?= $profile->adres?>" class="form-control"/>
            <input id="postcode" type="text" name="postcode" value="<?= $profile->postcode?>" class="form-control"/>
            <input id="plaats" type="text" name="plaats" value="<?= $profile->plaats?>" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2">
            <label class="control-label">Zichtbaar in Profiel</label>
        </div>
        <div class="col-sm-10">
            <label class="checkbox inline"><input type="checkbox" class="form-control" name="zichtbaar_telefoonnummer" value="1" <?= ($profile->zichtbaar_telefoonnummer)? 'checked':''?>> Laat mijn telefoonnummer zien</label>
            <label class="checkbox inline"><input type="checkbox" class="form-control" name="zichtbaar_email" value="1" <?= ($profile->zichtbaar_email)? 'checked':''?>> Laat mijn email zien</label>
            <label class="checkbox inline"><input type="checkbox" class="form-control" name="zichtbaar_adres" value="1" <?= ($profile->zichtbaar_adres)? 'checked':''?>> Laat mijn adres zien</label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2">
            <label class="control-label">Nieuwsbrief</label>
        </div>
        <div class="col-sm-10">
            <input type="checkbox" name="nieuwsbrief" value="1" <?= ($profile->nieuwsbrief)? 'checked':''?>> Ik wil graag de nieuwsbrief ontvangen
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2">
            <label class="control-label">English</label>
        </div>
        <div class="col-sm-10">
            <input type="checkbox" name="engels" value="1" <?= ($profile->engels)? 'checked':''?>> I want to receive content in English
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10">
            <input type="submit" class="btn btn-primary" value="Opslaan">
            <input type="reset" class="btn btn-warning" onclick="window.location.replace(document.referrer)" value="Annuleren">
        </div>
    </div>
<?= form_close(); ?>