<script>
    function update(tekst) {
        if (tekst === "0") {
            document.getElementById("labelna").innerHTML = "Onder welk menu";
        } else {
            document.getElementById("labelna").innerHTML = "In het menu na";
        }
    }
</script>

<div style="text-align:right; padding: 20px;"><a href="/beheer"><b>Terug</b></a></div>
<?php
if ($edit_mode) {
    echo form_open_multipart("/beheer/pagina/save_edit");
} else echo form_open_multipart("/beheer/pagina/save_toevoegen")
?>
<?php if ($edit_mode) { ?>
    <input type="hidden" name="id" value="<?= $page->id ?>"/>
<?php } ?>
<div class="form-group">
    <label for="naam" class="col-md-2 col-form-label">Nederlandse naam</label>
    <div class="col-md-10">
        <input class="form-control" type="text" name="naam" id="naam" value="<?= ($edit_mode) ? $page->naam : '' ?>"
               placeholder="Naam">
    </div>
</div>
<div class="form-group">
    <label for="englelse_naam" class="col-md-2 col-form-label">Engelse naam</label>
    <div class="col-md-10">
        <input class="form-control" type="text" name="engelse_naam" id="engelse_naam"
               value="<?= ($edit_mode) ? $page->engelse_naam : '' ?>" placeholder="Name">
    </div>
</div>
<div class="form-group">
    <label for="bereikbaar" class="col-md-2 col-form-label">Bereikbaar</label>
    <div class="col-md-10">
        <label ><input required <?= ($edit_mode && $page->bereikbaar === 'ja') ? 'checked' : '' ?>
                                           type="radio" name="bereikbaar" id="bereikbaar" value="ja">Ja</label>
        <label ><input
                    required <?= ($edit_mode && $page->bereikbaar === 'nee') ? 'checked' : '' ?> type="radio"
                    name="bereikbaar" value="nee">Nee</label>
    </div>
</div>
<div class="form-group">
    <label for="zichtbaar" class="col-md-2 col-form-label">Zichtbaar in menu</label>
    <div class="col-md-10">
        <label ><input required <?= ($edit_mode && $page->zichtbaar === 'ja') ? 'checked' : '' ?>
                                           type="radio" name="zichtbaar" id="zichtbaar" value="ja">Ja</label>
        <label ><input required <?= ($edit_mode && $page->zichtbaar === 'nee') ? 'checked' : '' ?>
                                           type="radio" name="zichtbaar" value="nee">Nee</label>
    </div>
</div>
<div class="form-group">
    <label for="hoofdmenu" class="col-md-2 col-form-label">Hoofdmenu</label>
    <div class="col-md-10">
        <label ><input required <?= ($edit_mode && $page->submenu === 'A') ? 'checked' : '' ?>
                                           type="radio" onchange="update(this.value)" name="hoofdmenu" id="hoofdmenu"
                                           value="1">Ja</label>
        <label ><input required <?= ($edit_mode && $page->submenu !== 'A') ? 'checked' : '' ?>
                                           type="radio" onchange="update(this.value)" name="hoofdmenu"
                                           value="0">Nee</label>
    </div>
</div>
<div class="form-group">
    <label for="ingelogd" class="col-md-2 col-form-label">Moet ingelogd zijn</label>
    <div class="col-md-10">
        <label ><input required <?= ($edit_mode && $page->ingelogd) ? 'checked' : '' ?>
                                           type="radio" name="ingelogd" id="ingelogd" value="1">Ja</label>
        <label ><input required <?= ($edit_mode && !$page->ingelogd) ? 'checked' : '' ?>
                                           type="radio" name="ingelogd" value="0">Nee</label>
    </div>
</div>
<div class="form-group">
    <label id="labelna" for="na" class="col-md-2 col-form-label">In het menu na</label>
    <div class="col-md-10">
        <select class="form-control" id="na" name="na">
            <?php foreach ($hoofdmenu as $optie) { ?>
                <option value="<?= $optie['id'] ?>"
                        <?= ($edit_mode && $page->submenu === $optie['id']) ? 'selected' : '' ?>><?= $optie['naam'] ?></option>
            <?php } ?>
        </select>
    </div>
</div>
<?php if ($edit_mode && $page->cmspagina === 'ja' || !$edit_mode) { ?>
    <div class="form-group">
        <label for="summernote" class="col-md-2 col-form-label">Nederlands</label>
        <div class="col-md-10">
			<textarea class="input-block-level" id="summernote"
                      name="tekst"><?= ($edit_mode) ? $page->tekst : '' ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="summernote" class="col-md-2 col-form-label">Engels</label>
        <div class="col-md-10">
			<textarea class="input-block-level" id="engels"
                      name="engels"><?= ($edit_mode) ? $page->engels : '' ?></textarea>
        </div>
    </div>
<?php } ?>
<div class="form-group">
    <div class="col-md-10 float-right">
        <button type="submit" id="submit"
                class="btn btn-primary center-block btn-lg"><?= ($edit_mode) ? 'Opslaan' : 'Toevoegen' ?></button>
    </div>
</div>
<?= form_close(); ?>
