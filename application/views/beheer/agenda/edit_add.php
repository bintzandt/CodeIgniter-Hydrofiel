<html>
<body>
<script>
    $(document).ready(function () {
        let slag = $("#slag");
        let edit_mode = <?= $edit_mode ? 'true' : 'false' ?>;

        if (!edit_mode) {
            for (i = 0; i < 10; i++) {
                slag.append("<div class=\"input-group date\"><input type=\"text\" class=\"form-control\" name=\"slagen[]\"><span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-trash\"></i></span></div>"); //add input box
            }
        }

        slag.on("click", ".input-group-addon", function (e) {
            e.preventDefault();
            $(this).closest("div").remove();
        });
    });
</script>
<script lang="text/javascript" src="/assets/components/agenda.js"></script>

<div style="text-align:right; vertical-align: top; padding: 20px;"><a href="/beheer/agenda"><b>Terug</b></a></div>
<?= $edit_mode ? form_open_multipart(
    '/beheer/agenda/save',
    [
        "role" => "form",
    ]
) : form_open_multipart('/beheer/agenda/submit', ["role" => "form"]) ?>
<?php if ($edit_mode) { ?>
    <input type="hidden" name="event_id" value="<?= $event->event_id ?>">
<?php } ?>
<div class="form-group">
    <label class="col-md-2 col-form-label" for="nl_naam">Naam (NL)</label>
    <div class="col-md-10">
        <input type="text" class="form-control" name="nl_naam" id="nl_naam" placeholder="Naam"
               value="<?= ($edit_mode) ? $event->nl_naam : '' ?>">
    </div>
</div>
<div class="form-group">
    <label for="summernote" class="col-md-2 col-form-label">Omschrijving (NL)</label>
    <div class="col-md-10">
		<textarea class="input-block-level" id="summernote" name="nl_omschrijving"
                  required><?= ($edit_mode) ? $event->nl_omschrijving : '' ?></textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 col-form-label" for="en_naam">Naam (EN)</label>
    <div class="col-md-10">
        <input type="text" class="form-control" name="en_naam" id="en_naam" placeholder="Naam"
               value="<?= ($edit_mode) ? $event->en_naam : '' ?>">
    </div>
</div>
<div class="form-group">
    <label for="engels" class="col-md-2 col-form-label">Omschrijving (EN)</label>
    <div class="col-md-10">
		<textarea class="input-block-level" id="engels" name="en_omschrijving"
                  required><?= ($edit_mode) ? $event->en_omschrijving : '' ?></textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 col-form-label" for="soort">Soort</label>
    <div class="col-md-10">
        <select class="form-control" id="soort" name="soort">
            <option value="algemeen" <?= ($edit_mode && $event->soort === "algemeen") ? 'selected' : '' ?>>Algemeen
            </option>
            <option value="toernooi" <?= ($edit_mode && $event->soort === "toernooi") ? 'selected' : '' ?>>Toernooi
            </option>
            <option value="nszk" <?= ($edit_mode && $event->soort === "nszk") ? 'selected' : '' ?>>NSZK</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 col-form-label" for="van">Van/Tot</label>
    <div class="col-md-10">
        <div class="input-daterange input-group" id="datepicker">
            <input type="text" class="form-control-sm form-control" name="van" id="van"
                   value="<?= ($edit_mode) ? date_format(date_create($event->van), 'd-m-Y H:i') : '' ?>"/>
            <span class="input-group-addon">tot</span>
            <input type="text" class="form-control-sm form-control" name="tot" id="tot"
                   value="<?= ($edit_mode) ? date_format(date_create($event->tot), 'd-m-Y H:i') : '' ?>"/>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 col-form-label" for="link">Link</label>
    <div class="col-md-10">
        <input type="text" class="form-control" name="link" id="link"
               placeholder="Link naar het Facebook-evenement of website"
               value="<?= ($edit_mode) ? $event->link : '' ?>">
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 col-form-label" for="locatie">Locatie</label>
    <div class="col-md-10">
        <input type="text" class="form-control" name="locatie" id="locatie" placeholder="Locatie"
               value="<?= ($edit_mode) ? $event->locatie : '' ?>">
    </div>
</div>
<div class="form-group">
    <label for="inschrijven" class="col-md-2 col-form-label">Inschrijven mogelijk</label>
    <div class="col-md-10">
        <label ><input required type="radio" name="inschrijfsysteem"
                                           onchange="toggleInschrijf($(this).val())" id="inschrijven"
                                           value="1" <?= ($edit_mode && $event->inschrijfsysteem) ? 'checked' : '' ?>>Ja</label>
        <label ><input required type="radio" name="inschrijfsysteem"
                                           onchange="toggleInschrijf($(this).val())"
                                           value="0" <?= ($edit_mode && !$event->inschrijfsysteem) ? 'checked' : '' ?>>Nee</label>
    </div>
</div>
<div class="form-group <?= ($edit_mode && $event->inschrijfsysteem) ? '' : 'hidden' ?>" id="inschrijfdeadline">
    <label for="inschrijfdeadline" class="col-md-2 col-form-label">Inschrijfdeadline</label>
    <div class="col-md-10">
        <div class="input-group date">
            <input type="text" class="form-control" name="inschrijfdeadline" id="inschrijf"
                   value="<?= ($edit_mode && $event->inschrijfsysteem) ? date_format(
                       date_create($event->inschrijfdeadline),
                       'd-m-Y H:i'
                   ) : '' ?>"><span
                    class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
        </div>
    </div>
</div>
<div class="form-group <?= ($edit_mode && $event->inschrijfsysteem) ? '' : 'hidden' ?>" id="afmelddeadline">
    <label for="afmelddeadline" class="col-md-2 col-form-label">Afmelddeadline</label>
    <div class="col-md-10">
        <div class="input-group date">
            <input type="text" class="form-control" name="afmelddeadline" id="afmeld"
                   value="<?= ($edit_mode && $event->inschrijfsysteem && $event->afmelddeadline) ? date_format(
                       date_create($event->afmelddeadline),
                       'd-m-Y H:i'
                   ) : '' ?>"><span
                    class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="betalen" class="col-md-2 col-form-label">Betaalde activiteit</label>
    <div class="col-md-10">
        <label ><input required type="radio" name="betalen" id="betalen"
                                           value="1" <?= ($edit_mode && $event->betalen) ? 'checked' : '' ?>>Ja</label>
        <label ><input required type="radio" name="betalen"
                                           value="0"
                                           <?= ($edit_mode && !$event->betalen) ? 'checked' : '' ?>>Nee</label>
    </div>
</div>
<div class="form-group">
    <label for="maximum" class="col-md-2 col-form-label">Maximum aantal aanmeldingen</label>
    <div class="col-md-10">
        <input type="number" value="<?= ($edit_mode) ? $event->maximum : 0 ?>" id="maximum" name="maximum"
               class="form-control" min="0">
        <span class="form-text">De standaardwaarde 0 betekent dat er geen limiet is op het aantal aanmeldingen!</span>
    </div>
</div>
<div id="nszk" class="<?= ($edit_mode && $event->soort === 'nszk') ? '' : 'hidden' ?>">
    <div class="form-group">
        <label for="slagen" class="col-md-2 col-form-label">Slagen</label>
        <div class="col-md-10" id="wrapper">
            <?php if ($edit_mode) { ?>
                <div id="slag">
                    <?php $slagen = json_decode($event->slagen);
                    foreach ($slagen as $slag) { ?>
                        <input type="text" class="form-control" id="slagen" name="slagen[]" value="<?= $slag ?>">
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div id="slag">
                    <input type="text" class="form-control" id="slagen" name="slagen[]">
                </div>
            <?php } ?>
            <button type="button" class="btn btn-primary form-control" id="add_button">Slag toevoegen</button>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 col-form-label"></label>
    <div class="col-md-10">
        <button class="btn btn-primary center-block btn-lg"
                type="submit"><?= ($edit_mode) ? 'Opslaan' : 'Toevoegen' ?></button>
    </div>
</div>
<?= form_close(); ?>
</body>
</html>
