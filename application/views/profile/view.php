<h3><?= lang(
        "profile_title"
    ) ?><?= $profile->naam ?><?= is_admin_or_requested_user( $profile->id ) ? ' <a href="/profile/edit/' . $profile->id . '"><span class="fa fa-pencil-alt"></span></a>' : '' ?><?= is_admin() ? ' <a href="/beheer/leden/delete/' . $profile->id . '"><span class="fa fa-trash"></span></a>' : '' ?></h3>
<div class="table-responsive">
    <table class="table table-sm table-responsive table-user-information">
        <tbody>
        <tr>
            <td>
                <strong>
                    <span class="glyphicon glyphicon-user  text-primary"></span>
                    <?= lang("profile_name"); ?>
                </strong>
            </td>
            <td class="text-primary">
                <?= $profile->naam ?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                    <span class="glyphicon glyphicon-gift  text-primary"></span>
                    <?= lang("profile_birthday"); ?>
                </strong>
            </td>
            <td class="text-primary">
                <?= $profile->geboortedatum ?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                    <span class="glyphicon glyphicon-envelope text-primary"></span>
                    <?= lang("profile_email"); ?>
                </strong>
            </td>
            <td class="text-primary">
                <?= $profile->email ?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                    <span class="glyphicon glyphicon-tint text-primary"></span>
                    <?= lang("profile_membership"); ?>
                </strong>
            </td>
            <td class="text-primary">
                <?= $profile->lidmaatschap ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
