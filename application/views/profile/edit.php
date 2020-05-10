<?php echo validation_errors(); ?>
<script src="/assets/webauthnauthenticate.js"></script>
<script src="/assets/webauthnregister.js"></script>
<script>
    function hasWebAuthnSupport() {
        return (window.PublicKeyCredential !== undefined || typeof window.PublicKeyCredential === "function");
    }

    $(document).ready(function () {
        let webauth_btn = $("#webauthn-add-button");

        if (hasWebAuthnSupport()) {
            webauth_btn.removeClass("hidden");
        }

        webauth_btn.click(async function () {
            $.ajax({
                method: "GET",
                url: "webauthn/get_registration_challenge",
                dataType: "json",
                success: function (response) {
                    webauthnRegister(response, function (success, info) {
                        if (success) {
                            $.ajax({
                                method: "POST",
                                url: "/webauthn/register",
                                data: {register: info},
                                dataType: "json",
                                success: function (r) {
                                    console.debug(r);
                                    alert("registration success!");
                                },
                                error: function (xhr, status, error) {
                                    alert("registration failed: " + error + ": " + xhr.responseText);
                                },
                            });
                        } else {
                            alert(info);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    alert("couldn't initiate registration: " + error + ": " + xhr.responseText);
                },
            });
        });
    });
</script>
<h3><?= lang('profile_title') ?><?= $profile->naam ?></h3>
<?= form_open("/profile/save/" . $profile->id, ["class" => "form"]); ?>
<div class="form-group">
    <div class="col-md-2">
        <label class="col-form-label" for="naam"><?= lang("profile_name") ?></label>
    </div>
    <div class="col-md-10">
        <input id="naam" type="text" class="form-control" disabled value="<?= $profile->naam ?>">
        <input name="naam" type="hidden" value="<?= $profile->naam ?>">
    </div>
</div>
<div class="form-group">
    <div class="col-md-2">
        <label class="col-form-label" for="wachtwoord"><?= lang("profile_password") ?></label>
    </div>
    <div class="col-md-10">
        <input id="wachtwoord" name="wachtwoord1" type="password" class="form-control" placeholder="wachtwoord" autocomplete="new-password">
        <input id="wachtwoord2" name="wachtwoord2" type="password" class="form-control" placeholder="wachtwoord" autocomplete="new-password">
        <span class="form-text"><?= lang("profile_password_help") ?></span>
    </div>
</div>
<div class="form-group">
    <div class="col-md-2">
        <label class="col-form-label" for="email"><?= lang("profile_email") ?></label>
    </div>
    <div class="col-md-10">
        <input id="email" type="text" name="email" value="<?= $profile->email ?>" class="form-control" autocomplete="email">
    </div>
</div>
<div class="form-group">
    <div class="col-md-2">
        <label class="col-form-label"><?= lang("profile_visible") ?></label>
    </div>
    <div class="col-md-10">
        <input type="checkbox" name="zichtbaar_email"
               value="1" <?= $profile->zichtbaar_email ?>> <?= lang("profile_show_email") ?>
    </div>
</div>
<div class="form-group">
    <div class="col-md-2">
        <label class="col-form-label"><?= lang("profile_newsletter") ?></label>
    </div>
    <div class="col-md-10">
        <input type="checkbox" name="nieuwsbrief"
               value="1" <?= $profile->nieuwsbrief ?>> <?= lang("profile_newsletter_msg") ?>
    </div>
</div>
<div class="form-group">
    <div class="col-md-2">
        <label class="col-form-label">English</label>
    </div>
    <div class="col-md-10">
        <input type="checkbox" name="engels" value="1" <?= $profile->engels ?>> I want to receive
        content in English
    </div>
</div>
<div class="form-group">
    <div class="col-md-10">
        <input type="submit" class="btn btn-primary" value="<?= lang('profile_save') ?>">
        <input type="reset" class="btn btn-warning" onclick="window.location.replace(document.referrer)"
               value="<?= lang('profile_cancel') ?>">
        <input type="button" class="btn btn-success hidden" id="webauthn-add-button"
               value="Add a FIDO2 key"/>
    </div>
</div>
<?= form_close(); ?>
