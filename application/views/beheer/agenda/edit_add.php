<html>
<body>
<script>
    $(document).ready(function() {
        $('#rangepicker .input-daterange').datepicker({
            format: "dd-mm-yyyy",
            maxViewMode: 2,
            language: "nl",
            autoclose: true,
            todayHighlight: true
        });

        $('#inschrijfdeadline .input-group.date').datepicker({
            format: "dd-mm-yyyy",
            maxViewMode: 2,
            language: "nl",
            autoclose: true,
            todayHighlight: true,
        });

        $('#soort').change(function() {
            // show current
            if ($(this).val()==='nszk'){
                $('#nszk').toggleClass('hidden', false);
            } else {
                $('#nszk').toggleClass('hidden', true);
            }

        });

        for (i=0; i < 10; i++){
            $(slag).append('<div class="input-group date"><input type="text" class="form-control" name="slagen[]"><span class="input-group-addon"><i class="glyphicon glyphicon-trash"></i></span></div>'); //add input box
        }

        $('#add_button').click(function(e){ //on add input button click
            e.preventDefault();
            $(slag).append('<div class="input-group date"><input type="text" class="form-control" name="slagen[]"><span class="input-group-addon"><i class="glyphicon glyphicon-trash"></i></span></div>'); //add input box
        });

        $(slag).on("click", ".input-group-addon", function(e){
            e.preventDefault(); $(this).closest('div').remove();
        })

    });
    function toggleInschrijf(val){
        if (val==="1") $('#deadline').toggleClass('hidden', false);
        else {
            $('#deadline').toggleClass('hidden', true);
        }
    }
</script>

<div style="text-align:right; vertical-align: top; padding: 20px;"><a href="/beheer/agenda"><b>Terug</b></a></div>
<?= form_open_multipart("<?= ($edit_mode) ? '/agenda/save':'/agenda/submit'?>", array("class" => "form-horizontal", "role" => "form")); ?>
    <?php if ($edit_mode) { ?>
        <input type="hidden" name="event_id" value="<?= $event->event_id ?>">
    <?php } ?>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="naam">Naam</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="naam" id="naam" placeholder="Naam" value="<?= ($edit_mode) ? $event->naam : ''?>">
        </div>
    </div>
    <div class="form-group">
        <label for="summernote" class="col-sm-2 control-label">Omschrijving</label>
        <div class="col-sm-10">
            <textarea class="input-block-level" id="summernote" name="omschrijving" required><?= ($edit_mode) ? $event->omschrijving : ''?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="soort">Soort</label>
        <div class="col-sm-10">
            <select class="form-control" id="soort" name="soort">
                <option value="algemeen" <?= ($edit_mode && $event->soort==="algemeen") ? 'selected' : ''?>>Algemeen</option>
                <option value="toernooi" <?= ($edit_mode && $event->soort==="toernooi") ? 'selected' : ''?>>Toernooi</option>
                <option value="nszk" <?= ($edit_mode && $event->soort==="nszk") ? 'selected' : ''?>>NSZK</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="van">Van/Tot</label>
        <div class="col-sm-10" id="rangepicker">
            <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm form-control" name="van" id="van" value="<?= ($edit_mode) ? date_format(date_create($event->van), 'd-m-Y'): ''?>"/>
                <span class="input-group-addon">tot</span>
                <input type="text" class="input-sm form-control" name="tot" value="<?= ($edit_mode) ? date_format(date_create($event->tot), 'd-m-Y'): ''?>"/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="link">Link</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="link" id="link" placeholder="Link naar de activiteitpagina" value="<?= ($edit_mode) ? $event->link: ''?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="locatie">Locatie</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="locatie" id="locatie" placeholder="Locatie" value="<?= ($edit_mode) ? $event->locatie : ''?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inschrijven" class="col-sm-2 control-label">Inschrijven mogelijk</label>
        <div class="col-sm-10">
            <label class="radio-inline"><input required type="radio" name="inschrijfsysteem" onchange="toggleInschrijf($(this).val())" id="inschrijven" value="1" <?= ($edit_mode && $event->inschrijfsysteem) ? 'checked' : ''?>>Ja</label>
            <label class="radio-inline"><input required type="radio" name="inschrijfsysteem" onchange="toggleInschrijf($(this).val())" value="0" <?= ($edit_mode && !$event->inschrijfsysteem) ? 'checked' : ''?>>Nee</label>
        </div>
    </div>
    <div class="form-group <?= ($edit_mode && $event->inschrijfsysteem) ? '' :'hidden'?>" id="deadline">
        <label for="inschrijfdeadline" class="col-sm-2 control-label">Inschrijf deadline</label>
        <div class="col-sm-10" id="inschrijfdeadline">
            <div class="input-group date">
                <input type="text" class="form-control" name="inschrijfdeadline" value="<?= ($edit_mode && $event->inschrijfsysteem) ? date_format(date_create($event->inschrijfdeadline), 'd-m-Y') : ''?>"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="betalen" class="col-sm-2 control-label">Betaalde activiteit</label>
        <div class="col-sm-10">
            <label class="radio-inline"><input required type="radio" name="betalen" id="betalen" value="1" <?= ($edit_mode && $event->betalen) ? 'checked' : ''?>>Ja</label>
            <label class="radio-inline"><input required type="radio" name="betalen" value="0" <?= ($edit_mode && !$event->betalen) ? 'checked' : ''?>>Nee</label>
        </div>
    </div>
    <div id="nszk" class="<?= ($edit_mode && $event->soort === 'nszk')? '' : 'hidden'?>">
        <div class="form-group">
            <label for="slagen" class="col-sm-2 control-label">Slagen</label>
            <div class="col-sm-10" id="wrapper">
                <?php if($edit_mode) { $slagen = json_decode($event->slagen); foreach ($slagen as $slag) { ?>
                    <input type="text" class="form-control" id="slagen" name="slagen[]" value="<?=$slag?>">
                <?php }} else { ?>
                <div id="slag">
                    <input type="text" class="form-control" id="slagen" name="slagen[]">
                </div>
                <?php } ?>
                <button type="button" class="btn btn-primary form-control" id="add_button">Slag toevoegen</button>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <button class="btn btn-primary center-block btn-lg" type="submit"><?= ($edit_mode)?'Opslaan':'Toevoegen'?></button>
        </div>
    </div>
<?= form_close(); ?>
</body>
</html>