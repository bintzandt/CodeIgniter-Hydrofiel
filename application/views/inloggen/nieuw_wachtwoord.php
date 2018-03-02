<div class="row" style="width: 100%">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <?php if (isset($success)) { ?>
            <div class="alert alert-success">
                <strong><?= $success ?></strong>
            </div>
        <?php } if (isset($fail)) { ?>
            <div class="alert alert-danger">
                <strong><?= $fail?></strong>
            </div>
        <?php } ?>
        <?php echo validation_errors(); ?>
        <?= form_open("/inloggen/reset/" . $recovery, array("class" => "form-signin")); ?>
            <input type="hidden" name="recovery" value="<?=$recovery?>">
            <input type="password" name="wachtwoord1" class="form-control" placeholder="Wachtwoord">
            <input type="password" name="wachtwoord2" class="form-control" placeholder="Herhaal wachtwoord">
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">
                Wachtwoord opslaan</button>
        <?= form_close(); ?>
    </div>
</div>