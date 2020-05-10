<div style="padding-bottom: 15px">
    <h3>Bestanden uploaden</h3><br>
    <?= form_open_multipart("/beheer/upload/files") ?>
    <input name="userfile[]" type="file" multiple><br><br>
    <button class="btn btn-primary" type="submit">Uploaden</button>
    <br>
    <?= form_close() ?>
</div>
<hr>
<div class="row">
    <div class="col-lg-8">
        <h3>Foto's</h3>
        <table class="table table-sm table-striped table-hover table-responsive">
            <thead>
            <tr>
                <th>Preview</th>
                <th class="d-xs-none">Naam</th>
                <th>Beheer</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($images as $item) { ?>
                <tr>
                    <td><a href="<?= $item->url ?>" target="_blank"><img src="<?= $item->thumb ?>"
                                                                         style="max-width: 60px"></a></td>
                    <td class="d-xs-none" onclick="CopyToClipboard('<?= $item->url ?>')"
                        style="cursor: pointer"><?= $item->naam ?></td>
                    <td><a onclick="showModal('<?= $item->naam ?>', '<?= $item->deleteUrl ?>')"><i
                                    class="fa fa-trash"></i></a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="col-lg-4">
        <h3>Bestanden</h3>
        <table class="table table-sm table-striped table-hover table-responsive">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Beheer</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($files as $file) { ?>
                <tr>
                    <td onclick="CopyToClipboard('<?= $file->url ?>')" style="cursor: pointer"><?= $file->naam ?></td>
                    <td><a onclick="showModal('<?= $file->naam ?>', '<?= $file->deleteUrl ?>')"><i
                                    class="fa fa-trash"></i></a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function showModal(naam, delete_url) {
        showBSModal({
            title: "Weet je het zeker?",
            body: "Het bestand '" + naam + "' zal verwijderd worden! ",
            actions: [{
                label: "Ja",
                cssClass: "btn-danger",
                onClick: function (e) {
                    window.location.replace(delete_url);
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

    function CopyToClipboard(link) {
        // Cut off https://www.hydrofiel.nl/.
        //link = link.slice(26, link.length);
        var success = true,
            range = document.createRange(),
            selection;
        var tmpElem = $("<div>");
        tmpElem.css({
            position: "absolute",
            left: "-1000px",
            top: "-1000px",// Add the input value to the temp element.
        });
        tmpElem.text(link);
        $("body").append(tmpElem);
        // Select temp element.
        range.selectNodeContents(tmpElem.get(0));
        selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
        // Lets copy.
        success = document.execCommand("copy", false, null);
        if (success) {
            alert("Link is naar klembord gekopieerd!");
        }
    }
</script>
