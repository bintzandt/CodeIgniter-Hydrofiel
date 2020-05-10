<div style="text-align:right; vertical-align: top; padding: 20px;"><a
            href="/beheer/agenda/inschrijvingen/<?= $event_id ?>"><b>Terug</b></a></div>
<?= form_open(); ?>
<div class="form-group">
    <div class="col-md-4">
        <label class="col-form-label" for="naam">Naam</label>
    </div>
    <div class="col-md-8">
        <input disabled id="naam" class="form-control" type="text" value="<?= $inschrijving->naam ?>">
    </div>
</div>
<div class="form-group">
    <div class="col-md-4">
        <label class="col-form-label" for="opmerking">Opmerking</label>
    </div>
    <div class="col-md-8">
        <input disabled id="opmerking" class="form-control" type="text" value="<?= $inschrijving->opmerking ?>">
    </div>
</div>
<div class="<?= ($nszk) ? '' : 'd-none' ?>">
    <hr>
    <h3>Slagen</h3><br>
    <?php if (!empty($slagen)) {
        foreach ($slagen as $slag => $tijd) { ?>
            <div class="form-group">
                <div class="col-md-4">
                    <label class="col-form-label"><?= $slag ?></label>
                </div>
                <div class="col-md-8">
                    <input disabled class="form-control" type="text" value="<?= $tijd ?>">
                </div>
            </div>
        <?php }
    }
    if (!empty($details)) { ?>
        <hr>
        <h3>Details</h3><br>
        <div class="form-group">
            <div class="col-md-4">
                <label for="preborrel">Ik ga mee naar de preborrel</label>
            </div>
            <div class="col-md-8">
                <input type="radio" id="preborrel" name="preborrel" value="1"
                       disabled <?= ($details->preborrel) ? 'checked' : '' ?>> Ja
                <input type="radio" name="preborrel" value="0"
                       disabled <?= (!$details->preborrel) ? 'checked' : '' ?>> Nee
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                <label for="avondeten">Ik eet 's avonds mee</label>
            </div>
            <div class="col-md-8">
                <input type="radio" id="avondeten" name="avondeten" value="1"
                       disabled <?= ($details->avondeten) ? 'checked' : '' ?>> Ja
                <input type="radio" name="avondeten" value="0"
                       disabled <?= (!$details->avondeten) ? 'checked' : '' ?>> Nee
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                <label for="feest">Ik ga mee naar het feest</label>
            </div>
            <div class="col-md-8">
                <input type="radio" id="feest" name="feest" value="1"
                       disabled <?= ($details->feest) ? 'checked' : '' ?>> Ja
                <input type="radio" name="feest" value="0" disabled <?= (!$details->feest) ? 'checked' : '' ?>> Nee
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                <label for="slapen">Ik zou graag op de volgende dagen blijven slapen</label>
            </div>
            <div class="col-md-8">
                <input type="radio" id="slapen" name="slapen" value="0"
                       disabled <?= ($details->slapen === '0') ? 'checked' : '' ?>> Niet
                <input type="radio" id="slapen" name="slapen" value="1"
                       disabled <?= ($details->slapen === '1') ? 'checked' : '' ?>> Vrijdag
                <input type="radio" name="slapen" value="2"
                       disabled <?= ($details->slapen === '2') ? 'checked' : '' ?>> Zaterdag
                <input type="radio" name="slapen" value="3"
                       disabled <?= ($details->slapen === '3') ? 'checked' : '' ?>> Beide
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                <label for="groep_heen">Ik zou graag met de groep heen willen reizen</label>
            </div>
            <div class="col-md-8">
                <input type="radio" id="groep_heen" name="groep_heen" value="0"
                       disabled <?= ($details->groep_heen === '0') ? 'checked' : '' ?>> Ja, met een groep op vrijdag
                <input type="radio" name="groep_heen" value="1"
                       disabled <?= ($details->groep_heen === '1') ? 'checked' : '' ?>> Ja, met een groep op zaterdag
                <input type="radio" name="groep_heen" value="2"
                       disabled <?= ($details->groep_heen === '2') ? 'checked' : '' ?>> Nee
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                <label for="groep_terug">Ik zou graag met de groep terug willen reizen</label>
            </div>
            <div class="col-md-8">
                <input type="radio" id="groep_terug" name="groep_terug" value="0"
                       disabled <?= ($details->groep_terug === '0') ? 'checked' : '' ?>> Ja, met een groep op zaterdag
                <input type="radio" name="groep_terug" value="1"
                       disabled <?= ($details->groep_terug === '1') ? 'checked' : '' ?>> Ja, met een groep op zondag
                <input type="radio" name="groep_terug" value="2"
                       disabled <?= ($details->groep_terug === '2') ? 'checked' : '' ?>> Nee
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                <label for="speciaal">Heb je nog speciale wensen (dieet, vegetarisch..) waarover het bestuur contact
                    moet opnemen?</label>
            </div>
            <div class="col-md-8">
                <input type="radio" id="speciaal" name="speciaal" value="1"
                       disabled <?= ($details->speciaal) ? 'checked' : '' ?>> Ja
                <input type="radio" name="speciaal" value="0" disabled <?= (!$details->speciaal) ? 'checked' : '' ?>>
                Nee
            </div>
        </div>
    <?php } ?>
</div>
<?= form_close() ?>
