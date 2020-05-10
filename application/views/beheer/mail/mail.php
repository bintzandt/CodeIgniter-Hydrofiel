<?php if (isset($fail) && $fail) { ?>
    <div class="alert alert-danger">
        <strong><?= $fail ?></strong>
    </div>
<?php } ?>
<div style="text-align:right; vertical-align: top; padding: 20px;"><a
            href="/beheer/mail/history"><b>Geschiedenis</b></a></div>
<?php echo form_open_multipart('beheer/mail', ['id' => 'postForm']); ?>
<div class="form-group">
    <label for="aan" class="col-md-2 col-form-label">Aan</label>
    <div class="col-md-10">
        <select class="selectpicker form-control" id="aan" name="aan">
            <option value="bestuur" <?php echo set_select('aan', 'bestuur', true); ?>>Bestuur</option>
            <option value="nieuwsbrief" <?php echo set_select('aan', 'nieuwsbrief'); ?>>Nieuwsbrief</option>
            <option value="iedereen" <?php echo set_select('aan', 'iedereen'); ?>>Iedereen</option>
            <option value="zwemmers" <?php echo set_select('aan', 'zwemmers'); ?>>Zwemmers</option>
            <option value="waterpolo" <?php echo set_select('aan', 'waterpolo'); ?>>Waterpolo</option>
            <option value="waterpoloscompetitie" <?php echo set_select('aan', 'waterpoloscompetitie'); ?>>Waterpolo
                (competitie)
            </option>
            <option value="waterpolosrecreatief" <?php echo set_select('aan', 'waterpolosrecreatief'); ?>>Waterpolo
                (recreatief)
            </option>
            <option value="trainers" <?php echo set_select('aan', 'trainers'); ?>>Trainers</option>
            <option value="select" <?php echo set_select('aan', 'select'); ?>>Losse personen</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label for="van" class="col-md-2 col-form-label">Van</label>
    <div class="col-md-10">
        <select class="selectpicker form-control" id="van" name="van">
            <option value="bestuur" <?php echo set_select('van', 'bestuur', true); ?>>Bestuur</option>
            <option value="voorzitter" <?php echo set_select('van', 'voorzitter'); ?>>Voorzitter</option>
            <option value="secretaris" <?php echo set_select('van', 'secretaris'); ?>>Secretaris</option>
            <option value="penningmeester" <?php echo set_select('van', 'penningmeester'); ?>>Penningmeester</option>
            <option value="zwemmen" <?php echo set_select('van', 'zwemmen'); ?>>Zwemcommissaris</option>
            <option value="waterpolo" <?php echo set_select('van', 'waterpolo'); ?>>Waterpolocommissaris</option>
            <option value="algemeen" <?php echo set_select('van', 'algemeen'); ?>>Commissaris Algemeen</option>
            <option value="activiteiten" <?php echo set_select('van', 'activiteiten'); ?>>Activiteitencommissie
            </option>
        </select>
    </div>
</div>
<div class="form-group">
    <label for="email" class="col-md-2 col-form-label">Stuur ook naar</label>
    <div class="col-md-10">
        <input type="text" class="form-control" id="email" name="email" value="<?= set_value('email') ?>">
        <span class="form-text">Laat dit veld leeg als je de email niet apart wilt doorsturen.</span>
    </div>
</div>
<div class="form-group">
    <label for="los" class="col-md-2 col-form-label">Los</label>
    <div class="col-md-10">
        <select multiple class="form-control" id="los" name="los[]">
            <?php foreach ($leden as $lid) { ?>
                <option value="<?= $lid->id ?>" <?php echo set_select('los[]', $lid->id) ?>><?= $lid->naam ?></option>
            <?php } ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label for="layout" class="col-md-2 col-form-label">Layout</label>
    <div class="col-md-10">
        <select id="layout" class="form-control" name="layout">
            <option value="standaard" <?php echo set_select('layout', 'standaard', true); ?>>Standaard layout</option>
            <option value="nieuwsbrief" <?php echo set_select('layout', 'nieuwsbrief'); ?>>Nieuwsbrief layout</option>
            <option value="geen" <?php echo set_select('layout', 'geen'); ?>>Geen layout</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label for="onderwerp" class="col-md-2 col-form-label">Onderwerp (NL)</label>
    <div class="col-md-10">
        <?php echo form_error('onderwerp', '<div class="alert alert-danger">', '</div>'); ?>
        <input type="text" class="form-control" id="onderwerp" name="onderwerp" value="<?= set_value('onderwerp') ?>"
               required/>
    </div>
</div>
<div class="form-group">
    <label for="summernote" class="col-md-2 col-form-label">Tekst (NL)</label>
    <div class="col-md-10">
		<textarea class="input-block-level" id="summernote" name="content"
                  required><?php echo set_value('content') ?></textarea>
    </div>
</div>
<div class="form-group">
    <label for="file" class="col-md-2 col-form-label">Bijlage (NL)</label>
    <div class="col-md-10">
        <input type="file" name="userfile_nl[]" size="20" multiple>
    </div>
</div>
<div class="form-group">
    <label for="en_onderwerp" class="col-md-2 col-form-label">Onderwerp (EN)</label>
    <div class="col-md-10">
        <?php echo form_error('onderwerp', '<div class="alert alert-danger">', '</div>'); ?>
        <input type="text" class="form-control" id="en_onderwerp" name="en_onderwerp"
               value="<?= set_value('en_onderwerp') ?>" required/>
    </div>
</div>
<div class="form-group">
    <label for="engels" class="col-md-2 col-form-label">Tekst (EN)</label>
    <div class="col-md-10">
		<textarea class="input-block-level" id="engels" name="en_content"
                  required><?php echo set_value('en_content') ?></textarea>
    </div>
</div>
<div class="form-group">
    <label for="file" class="col-md-2 col-form-label">Bijlage (EN)</label>
    <div class="col-md-10">
        <input type="file" name="userfile_en[]" size="20" multiple>
        <input id="send" onclick="showModal()" type="button" class="btn btn-primary center-block" value="Versturen">
    </div>
</div>
</form>
<div style="text-align:right; vertical-align: top; padding: 20px;"><a href="/beheer/mail/vrienden"><b>Vrienden van
            Hydrofiel</b></a></div>
