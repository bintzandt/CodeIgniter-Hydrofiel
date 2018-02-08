<html>
<body>
<h3>Verdere informatie NSZK</h3>
<?= form_open_multipart("/agenda/nszk_inschrijven", array("class" => "form-horizontal", "role" => "form")); ?>
    <input type="hidden" name="nszk_id" value="<?= $nszk_id?>">
    <div class="form-group">
        <div class="col-sm-4">
            <label for="preborrel">Ik ga mee naar de preborrel</label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="preborrel" name="preborrel" value="1" required> Ja
            <input type="radio" name="preborrel" value="0" required> Nee
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="avondeten">Ik eet 's avonds mee</label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="avondeten" name="avondeten" value="1" required> Ja
            <input type="radio" name="avondeten" value="0" required> Nee
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="feest">Ik ga mee naar het feest</label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="feest" name="feest" value="1" required> Ja
            <input type="radio" name="feest" value="0" required> Nee
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="slapen">Ik zou graag op de volgende dagen blijven slapen</label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="slapen" name="slapen" value="0" required> Niet
            <input type="radio" id="slapen" name="slapen" value="1" required> Vrijdag
            <input type="radio" name="slapen" value="2" required> Zaterdag
            <input type="radio" name="slapen" value="3" required> Beide
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="groep_heen">Ik zou graag met de groep heen willen reizen</label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="groep_heen" name="groep_heen" value="0" required> Ja, met een groep op vrijdag
            <input type="radio" name="groep_heen" value="1" required> Ja, met een groep op zaterdag
            <input type="radio" name="groep_heen" value="2" required> Nee
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="groep_terug">Ik zou graag met de groep terug willen reizen</label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="groep_terug" name="groep_terug" value="0" required> Ja, met een groep op zaterdag
            <input type="radio" name="groep_terug" value="1" required> Ja, met een groep op zondag
            <input type="radio" name="groep_terug" value="2" required> Nee
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="speciaal">Heb je nog speciale wensen (dieet, vegetarisch..) waarover het bestuur contact moet opnemen?</label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="speciaal" name="speciaal" value="1" required> Ja
            <input type="radio" name="speciaal" value="0" required> Nee
        </div>
    </div>
    <button class="btn btn-primary center-block">Opslaan</button>
<?= form_close(); ?>
</body>