<div class="row" style="width: 100%">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <?= form_open("/inloggen/forgot_password", ["class" => "form-signin"]); ?>
        <?php echo form_error('email'); ?>
        <input type="text" name="email" class="form-control" placeholder="Email" autofocus>
        <button class="btn btn-lg btn-primary btn-block" type="submit">
            Reset <?= lang('inloggen_password'); ?></button>
        <?= form_close(); ?>
    </div>
</div>
