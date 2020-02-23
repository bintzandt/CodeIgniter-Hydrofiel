<div style="text-align:right; vertical-align: top; padding: 20px;"><a href="/beheer/mail"><b>Terug</b></a></div>
<?php if (isset($success)) { ?>
    <div class="alert alert-success">
        <strong><?= $success ?></strong>
    </div>
<?php }
if (isset($fail)) { ?>
    <div class="alert alert-danger">
        <strong><?= $fail ?></strong>
    </div>
<?php } ?>
<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Datum</th>
        <th>Onderwerp</th>
        <th>Van</th>
        <th>Verwijderen</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($email as $mail) { ?>
        <tr>
            <td class="clickable-row"
                data-href="/beheer/mail/view_mail/<?= $mail->hash ?>"><?= date_format(
                    date_create($mail->datum),
                    'd-m-Y H:i'
                ) ?></td>
            <td class="clickable-row" data-href="/beheer/mail/view_mail/<?= $mail->hash ?>"><?= $mail->onderwerp ?></td>
            <td class="clickable-row" data-href="/beheer/mail/view_mail/<?= $mail->hash ?>"><?= $mail->van ?></td>
            <td><a style="font-size: larger" onclick="showModal('<?= $mail->onderwerp ?>', '<?= $mail->hash ?>')"><span
                            class="fa fa-trash"></span></a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<script>
    function showModal(onderwerp, hash) {
        showBSModal({
            title: "Weet je het zeker?",
            body: "De mail met als onderwerp '" + onderwerp + "' zal verwijderd worden! ",
            actions: [{
                label: "Ja",
                cssClass: "btn-danger",
                onClick: function (e) {
                    window.location.replace("/beheer/mail/delete/" + hash);
                },
            }, {
                label: "Nee",
                cssClass: "btn-success",
                onClick: function (e) {
                    $(e.target).parents(".modal").modal("hide");
                },
            }],
        });
    }
</script>
