<div style="text-align:right; vertical-align: top; padding: 20px;"><a href="/beheer/mail"><b>Terug</b></a></div>
<?= form_open('/beheer/mail/save_vrienden', array("class" => "form-horizontal")); ?>
    <div class="form-group">
        <label for="vrienden" class="col-sm-2 control-label">Vrienden van Hydrofiel</label>
        <div class="col-sm-10">
            <textarea name="vrienden_van" id="vrienden" class="form-control" rows="7" cols="1"><?= $mailadressen ?></textarea>
        </div>
    </div>
    <button type="submit" class="btn btn-primary center-block">Opslaan</button>
<?= form_close(); ?>