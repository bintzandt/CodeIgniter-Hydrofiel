<div class="row justify-content-center">
    <div class="col-lg-4 col-md-6">
        <?= form_open("/inloggen/forgot_password", ["class" => "form-signin"]); ?>
        <?php echo form_error('email'); ?>
        <input type="text" name="email" class="form-control" placeholder="Email" autofocus>
        <button class="btn btn-lg btn-primary btn-block" type="submit">
            Reset <?= lang('inloggen_password'); ?></button>
        <?= form_close(); ?>
    </div>
</div>
