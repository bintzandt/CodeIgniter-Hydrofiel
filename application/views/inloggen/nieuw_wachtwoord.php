<div class="row justify-content-center">
    <div class="col-lg-4 col-md-6">
        <?php echo validation_errors(); ?>
        <?= form_open("/inloggen/reset/" . $recovery, ["class" => "form-signin"]); ?>
        <input type="hidden" name="recovery" value="<?= $recovery ?>">
        <input type="password" name="wachtwoord1" class="form-control"
               placeholder="<?= lang('inloggen_password'); ?>">
        <input type="password" name="wachtwoord2" class="form-control"
               placeholder="<?= lang('inloggen_confirm_password') ?>">
        <button class="btn btn-lg btn-primary btn-block" type="submit"
                name="submit"><?= lang('inloggen_save_password') ?></button>
        <?= form_close(); ?>
    </div>
</div>
