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
    echo form_open_multipart("/beheer/pagina/save_edit", ["class" => "form-horizontal"]);
} else echo form_open_multipart("/beheer/pagina/save_toevoegen", ["class" => "form-horizontal"])
?>
<?php if ($edit_mode) { ?>
    <input type="hidden" name="id" value="<?= $page->id ?>"/>
<?php } ?>
<div class="form-group">
    <label for="naam" class="col-sm-2 control-label">Nederlandse naam</label>
    <div class="col-sm-10">
        <input class="form-control" type="text" name="naam" id="naam" value="<?= ($edit_mode) ? $page->naam : '' ?>"
               placeholder="Naam">
    </div>
</div>
<div class="form-group">
    <label for="englelse_naam" class="col-sm-2 control-label">Engelse naam</label>
    <div class="col-sm-10">
        <input class="form-control" type="text" name="engelse_naam" id="engelse_naam"
               value="<?= ($edit_mode) ? $page->engelse_naam : '' ?>" placeholder="Name">
    </div>
</div>
<div class="form-group">
    <label for="bereikbaar" class="col-sm-2 control-label">Bereikbaar</label>
    <div class="col-sm-10">
        <label class="radio-inline"><input required <?= ($edit_mode && $page->bereikbaar === 'ja') ? 'checked' : '' ?>
                                           type="radio" name="bereikbaar" id="bereikbaar" value="ja">Ja</label>
        <label class="radio-inline"><input
                    required <?= ($edit_mode && $page->bereikbaar === 'nee') ? 'checked' : '' ?> type="radio"
                    name="bereikbaar" value="nee">Nee</label>
    </div>
</div>
<div class="form-group">
    <label for="zichtbaar" class="col-sm-2 control-label">Zichtbaar in menu</label>
    <div class="col-sm-10">
        <label class="radio-inline"><input required <?= ($edit_mode && $page->zichtbaar === 'ja') ? 'checked' : '' ?>
                                           type="radio" name="zichtbaar" id="zichtbaar" value="ja">Ja</label>
        <label class="radio-inline"><input required <?= ($edit_mode && $page->zichtbaar === 'nee') ? 'checked' : '' ?>
                                           type="radio" name="zichtbaar" value="nee">Nee</label>
    </div>
</div>
<div class="form-group">
    <label for="hoofdmenu" class="col-sm-2 control-label">Hoofdmenu</label>
    <div class="col-sm-10">
        <label class="radio-inline"><input required <?= ($edit_mode && $page->submenu === 'A') ? 'checked' : '' ?>
                                           type="radio" onchange="update(this.value)" name="hoofdmenu" id="hoofdmenu"
                                           value="1">Ja</label>
        <label class="radio-inline"><input required <?= ($edit_mode && $page->submenu !== 'A') ? 'checked' : '' ?>
                                           type="radio" onchange="update(this.value)" name="hoofdmenu"
                                           value="0">Nee</label>
    </div>
</div>
<div class="form-group">
    <label for="ingelogd" class="col-sm-2 control-label">Moet ingelogd zijn</label>
    <div class="col-sm-10">
        <label class="radio-inline"><input required <?= ($edit_mode && $page->ingelogd) ? 'checked' : '' ?>
                                           type="radio" name="ingelogd" id="ingelogd" value="1">Ja</label>
        <label class="radio-inline"><input required <?= ($edit_mode && !$page->ingelogd) ? 'checked' : '' ?>
                                           type="radio" name="ingelogd" value="0">Nee</label>
    </div>
</div>
<div class="form-group">
    <label id="labelna" for="na" class="col-sm-2 control-label">In het menu na</label>
    <div class="col-sm-10">
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
        <label for="summernote" class="col-sm-2 control-label">Nederlands</label>
        <div class="col-sm-10">
			<textarea class="input-block-level" id="summernote"
                      name="tekst"><?= ($edit_mode) ? $page->tekst : '' ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="summernote" class="col-sm-2 control-label">Engels</label>
        <div class="col-sm-10">
			<textarea class="input-block-level" id="engels"
                      name="engels"><?= ($edit_mode) ? $page->engels : '' ?></textarea>
        </div>
    </div>
<?php } ?>
<div class="form-group">
    <div class="col-sm-10 pull-right">
        <button type="submit" id="submit"
                class="btn btn-primary center-block btn-lg"><?= ($edit_mode) ? 'Opslaan' : 'Toevoegen' ?></button>
    </div>
</div>
<?= form_close(); ?>
