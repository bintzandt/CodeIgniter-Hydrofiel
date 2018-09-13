<h3><?= lang('agenda_further_information')?></h3>
<?= form_open_multipart("/agenda/nszk_inschrijven", array("class" => "form-horizontal", "role" => "form")); ?>
    <input type="hidden" name="nszk_id" value="<?= $nszk_id?>">
    <div class="form-group">
        <div class="col-sm-4">
            <label for="preborrel"><?= lang('agenda_preborrel')?></label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="preborrel" name="preborrel" value="1" required> <?= lang('agenda_yes'); ?>
            <input type="radio" name="preborrel" value="0" required> <?= lang('agenda_no'); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="avondeten"><?= lang('agenda_dinner') ?></label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="avondeten" name="avondeten" value="1" required> <?= lang('agenda_yes'); ?>
            <input type="radio" name="avondeten" value="0" required> <?= lang('agenda_no'); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="feest"><?= lang('agenda_party') ?></label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="feest" name="feest" value="1" required> <?= lang('agenda_yes'); ?>
            <input type="radio" name="feest" value="0" required> <?= lang('agenda_no'); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="slapen"><?= lang('agenda_sleep') ?></label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="slapen" name="slapen" value="0" required> <?= lang('agenda_not');?>
            <input type="radio" id="slapen" name="slapen" value="1" required> <?= lang('agenda_friday')?>
            <input type="radio" name="slapen" value="2" required> <?= lang('agenda_saturday') ?>
            <input type="radio" name="slapen" value="3" required> <?= lang('agenda_both')?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="groep_heen"><?= lang('agenda_travel_to') ?></label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="groep_heen" name="groep_heen" value="0" required> <?= lang('agenda_yes_friday')?>
            <input type="radio" name="groep_heen" value="1" required> <?= lang('agenda_yes_saturday') ?>
            <input type="radio" name="groep_heen" value="2" required> <?= lang('agenda_no'); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="groep_terug"><?= lang('agenda_travel_from') ?></label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="groep_terug" name="groep_terug" value="0" required> <?= lang('agenda_yes_saturday')?>
            <input type="radio" name="groep_terug" value="1" required> <?= lang('agenda_yes_sunday') ?>
            <input type="radio" name="groep_terug" value="2" required> <?= lang('agenda_no'); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <label for="speciaal"><?= lang('agenda_wishes') ?>/label>
        </div>
        <div class="col-sm-8">
            <input type="radio" id="speciaal" name="speciaal" value="1" required> <?= lang('agenda_yes'); ?>
            <input type="radio" name="speciaal" value="0" required> <?= lang('agenda_no'); ?>
        </div>
    </div>
    <button class="btn btn-primary center-block"><?= lang('agenda_save');?></button>
<?= form_close(); ?>