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
        <?php echo form_error('wachtwoord'); ?>
        <?php echo form_error('email'); ?>
        <?php echo form_open('/inloggen', array('class' => 'form-signin')); ?>
            <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo set_value('email'); ?>" autofocus>
            <input type="password" name="wachtwoord" id="wachtwoord" class="form-control" placeholder="<?= lang('inloggen_password') ?>">
            <button class="btn btn-lg btn-primary btn-block" type="submit"><?= lang('inloggen_login')?></button>
            <a href="/inloggen/forgot_password" class="pull-right need-help"><?= lang('inloggen_forgot_password')?></a><span class="clearfix"></span>
            <input type="hidden" name="redirect" value="<?= $redirect ?>">
        <?= form_close() ?>
    </div>
</div>