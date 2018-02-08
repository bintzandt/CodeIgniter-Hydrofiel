<div style="text-align:right; vertical-align: top; padding: 20px; cursor: pointer"><a href="/beheer/leden"><b>Terug</b></a></div>
<?php if (isset($error)){ ?>
    <div class="alert alert-danger">
        <strong><?= $error?></strong>
    </div>
<?php } ?>
<?php echo form_open_multipart('upload/import_users');?>

<input type="file" name="leden" size="20" />

<br /><br />

<input type="submit" class="btn btn-primary" value="Importeren" />

</form>