<h3><?= lang(
        "profile_title"
    ) ?><?= $profile->naam ?><?= is_admin_or_requested_user( $profile->id ) ? ' <a href="/profile/edit/' . $profile->id . '"><span class="fa fa-pencil-alt"></span></a>' : '' ?><?= is_admin() ? ' <a href="/beheer/leden/delete/' . $profile->id . '"><span class="fa fa-trash"></span></a>' : '' ?></h3>
<table class="table table-sm table-user-information">
    <tbody>
    <tr>
        <td>
            <strong>
                <span class="fa fa-user"></span>
                <?= lang("profile_name"); ?>
            </strong>
        </td>
        <td>
            <?= $profile->naam ?>
        </td>
    </tr>
    <tr>
        <td>
            <strong>
                <span class="fa fa-birthday-cake"></span>
                <?= lang("profile_birthday"); ?>
            </strong>
        </td>
        <td>
            <?= $profile->geboortedatum ?>
        </td>
    </tr>
    <tr>
        <td>
            <strong>
                <span class="fa fa-envelope"></span>
                <?= lang("profile_email"); ?>
            </strong>
        </td>
        <td>
            <?= $profile->email ?>
        </td>
    </tr>
    <tr>
        <td>
            <strong>
                <span class="fa fa-swimmer"></span>
                <?= lang("profile_membership"); ?>
            </strong>
        </td>
        <td>
            <?= $profile->lidmaatschap ?>
        </td>
    </tr>
    </tbody>
</table>
