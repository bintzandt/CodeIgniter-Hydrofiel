<!DOCTYPE HTML>
<html>
<body>
<script>
    $(document).ready(function() {
        $('#los').multiselect({
            enableCaseInsensitiveFiltering: true,
            maxHeight: 300,
            inheritClass: false,
            buttonWidth: '100%',
            numberDisplayed: 5,
            optionClass: function(element) {
                return 'multi';
            }
        });
    });

    function showModal(){
        var aan = "";
        var email = "";
        var names = "";
        var str = "";
        if ($('#aan option:selected').val()!=='select'){
            aan = "De mail wordt naar de groep " + $('#aan option:selected').text() + ' gestuurd.<br><br>';
        }
        if ($('#email').val() !== ""){
            email = "De mail wordt ook naar de volgende adressen gestuurd:<br>" + $('#email').val() + ".<br><br>";
        }
        $('#los option:selected').each(function() {
            // concat to a string with comma
            names += $(this).text() + ", ";
        });
        // trim comma and white space at the end of string
        if (names!=="") {
            names = names.slice(0, -2);
            names += ".";
            str = "De mail wordt ook naar de volgende personen gestuurd:<br>" + names;
        }

        showBSModal({
            title: "Controleer gegevens",
            body: aan + email + str,
            actions: [{
                label: 'Verstuur',
                cssClass: 'btn-primary',
                onClick: function(e){
                    $("#postForm").submit();
                }
            },{
                label: 'Annuleer',
                cssClass: 'btn-warning',
                onClick: function(e){
                    $(e.target).parents('.modal').modal('hide');
                }
            }]
        });
    }
</script>
<div style="text-align:right; vertical-align: top; padding: 20px;"><a href="/mail/history"><b>Geschiedenis</b></a></div>
<?php echo form_open_multipart('beheer/mail', array('id' => 'postForm', 'class' => 'form-horizontal'));?>
    <?php if (isset($success)) { ?>
        <div class="alert alert-success">
            <strong><?= $success ?></strong>
        </div>
    <?php } if (isset($fail)) { ?>
        <div class="alert alert-danger">
            <strong><?= $fail?></strong>
        </div>
    <?php } ?>
    <div class="form-group">
        <label for="aan" class="col-sm-2 control-label">Aan</label>
        <div class="col-sm-10">
            <select class="selectpicker form-control" id="aan" name="aan">
                <option value="bestuur"  <?php echo  set_select('aan', 'bestuur', TRUE); ?>>Bestuur</option>
                <option value="nieuwsbrief" <?php echo  set_select('aan', 'nieuwsbrief'); ?>>Nieuwsbrief</option>
                <option value="iedereen" <?php echo  set_select('aan', 'iedereen'); ?>>Iedereen</option>
                <option value="zwemmers" <?php echo  set_select('aan', 'zwemmers'); ?>>Zwemmers</option>
                <option value="waterpolo" <?php echo  set_select('aan', 'waterpolo'); ?>>Waterpolo</option>
                <option value="waterpoloscompetitie" <?php echo  set_select('aan', 'waterpoloscompetitie'); ?>>Waterpolo (competitie)</option>
                <option value="waterpolosrecreatief" <?php echo  set_select('aan', 'waterpolosrecreatief'); ?>>Waterpolo (recreatief)</option>
                <option value="trainers" <?php echo  set_select('aan', 'trainers'); ?>>Trainers</option>
                <option value="select" <?php echo  set_select('aan', 'select'); ?>>Losse personen</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="van" class="col-sm-2 control-label">Van</label>
        <div class="col-sm-10">
            <select class="selectpicker form-control" id="van" name="van">
                <option value="bestuur" <?php echo  set_select('van', 'bestuur', TRUE); ?>>Bestuur</option>
                <option value="voorzitter" <?php echo  set_select('van', 'voorzitter'); ?>>Voorzitter</option>
                <option value="secretaris" <?php echo  set_select('van', 'secretaris'); ?>>Secretaris</option>
                <option value="penningmeester" <?php echo  set_select('van', 'penningmeester'); ?>>Penningmeester</option>
                <option value="zwemmen" <?php echo  set_select('van', 'zwemmen'); ?>>Zwemcommissaris</option>
                <option value="waterpolo" <?php echo  set_select('van', 'waterpolo'); ?>>Waterpolocommissaris</option>
                <option value="algemeen" <?php echo  set_select('van', 'algemeen'); ?>>Commissaris Algemeen</option>
                <option value="activiteiten" <?php echo  set_select('van', 'activiteiten'); ?>>Activiteitencommissie</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-sm-2 control-label">Stuur ook naar</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="email" name="email" value="<?= set_value('email')?>">
            <span class="help-block">Laat dit veld leeg als je de email niet apart wilt doorsturen.</span>
        </div>
    </div>
    <div class="form-group">
        <label for="los" class="col-sm-2 control-label">Los</label>
        <div class="col-sm-10">
            <select multiple class="form-control" id="los" name="los[]">
                <?php foreach ($leden as $lid){?>
                    <option value="<?=$lid->id?>" <?php echo set_select('los[]', $lid->id)?>><?=$lid->naam?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="layout" class="col-sm-2 control-label">Layout</label>
        <div class="col-sm-10">
            <select id="layout" class="form-control" name="layout">
                <option value="standaard" <?php echo  set_select('layout', 'standaard', TRUE); ?>>Standaard layout</option>
                <option value="nieuwsbrief" <?php echo  set_select('layout', 'nieuwsbrief'); ?>>Nieuwsbrief layout</option>
                <option value="geen" <?php echo  set_select('layout', 'geen'); ?>>Geen layout</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="file" class="col-sm-2 control-label">Bijlage</label>
        <div class="col-sm-10">
            <input type="file" name="userfile[]" size="20" multiple>
        </div>
    </div>
    <div class="form-group">
        <label for="onderwerp" class="col-sm-2 control-label">Onderwerp (NL)</label>
        <div class="col-sm-10">
            <?php echo form_error('onderwerp', '<div class="alert alert-danger">', '</div>'); ?>
            <input type="text" class="form-control" id="onderwerp" name="onderwerp" value="<?= set_value('onderwerp')?>" required />
        </div>
    </div>
    <div class="form-group">
        <label for="summernote" class="col-sm-2 control-label">Tekst (NL)</label>
        <div class="col-sm-10">
            <textarea class="input-block-level" id="summernote" name="content" required><?php echo set_value('content')?></textarea>
<!--            <input id="send" onclick="showModal()" type="button" class="btn btn-primary center-block" value="Versturen">-->
        </div>
    </div>
    <div class="form-group">
        <label for="en_onderwerp" class="col-sm-2 control-label">Onderwerp (EN)</label>
        <div class="col-sm-10">
            <?php echo form_error('onderwerp', '<div class="alert alert-danger">', '</div>'); ?>
            <input type="text" class="form-control" id="en_onderwerp" name="en_onderwerp" value="<?= set_value('en_onderwerp')?>" required />
        </div>
    </div>
    <div class="form-group">
        <label for="engels" class="col-sm-2 control-label">Tekst (EN)</label>
        <div class="col-sm-10">
            <textarea class="input-block-level" id="engels" name="en_content" required><?php echo set_value('en_content')?></textarea>
            <input id="send" onclick="showModal()" type="button" class="btn btn-primary center-block" value="Versturen">
        </div>
    </div>
</form>
</body>
</html>