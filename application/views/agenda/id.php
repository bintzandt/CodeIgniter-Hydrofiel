<div class="container-fluid">
    <?php if (isset($success)) { ?>
        <div class="alert alert-success">
            <strong><?= $success ?></strong>
        </div>
    <?php } if (isset($fail)) { ?>
        <div class="alert alert-danger">
            <strong><?= $fail?></strong>
        </div>
    <?php } ?>
    <h3 align="center"><b><?= $event->naam ?></b></h3>
    <p><?= $event->omschrijving ?></p>
    <div class="col-sm-6" style="padding-left: 0">
        <h4>Details</h4>
        <table style="width:100%;">
            <tr>
                <td><b>Van</b></td>
                <td><?= date_format(date_create($event->van), 'd-m-Y')?></td>
            </tr>
            <tr>
                <td><b>Tot</b></td>
                <td><?= date_format(date_create($event->tot), 'd-m-Y') ?></td>
            </tr>
            <tr>
                <td><b>Locatie</b></td>
                <td><?= $event->locatie?></td>
            </tr>
            <?php if ($event->inschrijfsysteem) { ?>
                <tr>
                    <td><b>Deadline</b></td>
                    <td><?= date_format(date_create($event->inschrijfdeadline), 'd-m-Y') ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td><b>Soort</b></td>
                <td><?=$event->soort?></td>
            </tr>
        </table>
    </div>
    <div class="col-sm-6" style="padding-left: 0">
        <?php if(isset($inschrijvingen) && $event->inschrijfsysteem) { ?>
            <h4>Meest recente aanmeldingen</h4>
            <table style="width: 100%">
                <?php foreach ($inschrijvingen as $inschrijving) { ?>
                    <tr>
                        <td><?=$inschrijving->naam?></td>
                        <?php if ($inschrijving->opmerking !== "") { ?> <td><?=$inschrijving->opmerking?></td> <?php } ?>
                    </tr>
                <?php } ?>
            </table>
        <?php } elseif ($event->inschrijfsysteem) { ?>
            <br><br>
            <table style="width: 100%;">
                <tr>
                    <td>Er zijn nog geen aanmeldingen.</td>
                </tr>
            </table>
        <?php } else { ?>
            <br><br>
            <table style="width: 100%">
                <tr>
                    <td>Voor dit evenement hoef je je niet aan te melden.</td>
                </tr>
            </table>
        <?php } ?>
    </div>
    <div class="col-sm-12">
<?php if ($event->inschrijfsysteem) { ?>
        <?= form_open("<?= ($event->soort === 'nszk')? '/agenda/nszk':'/agenda/aanmelden'?>", array("id" => "aanmelden", "name" => "aanmelden")); ?>
        <input type="hidden" value="<?=$event->event_id?>" name="event_id"/>
        <input type="hidden" value="<?=$event->soort?>" name="event_soort">
    <?php if (!$aangemeld) { ?>
            <?php if ($event->soort === "nszk"){
                $slagen = json_decode($event->slagen);
                foreach ($slagen as $slag) { ?>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label><?=$slag?></label>
                        </div>
                        <div class="col-sm-8">
                            <input type="hidden" value="<?= $slag?>" name="slag[]">
                            <input type="text" class="form-control" name="tijd[]" placeholder="Tijd"/>
                        </div>
                    </div>
                <?php }
            } ?>
        <div class="form-group">
            <input type="text" name="opmerking" maxlength="20" class="form-control" placeholder="Opmerking">
            <?php if ($event->betalen) { ?>
                <input type="checkbox" required> Ik ga ermee akkoord dat Hydrofiel de kosten voor dit evenement afschrijft van mijn rekening.
            <?php } ?>
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-primary form-control">Aanmelden</button>
        </div>
    <?php } else { ?>
        <div class="form-group">
            <a href="/agenda/afmelden/<?= $event->event_id?>" class="btn btn-primary center-block">Afmelden</a>
        </div>
    <?php } ?>
<?php } echo form_close() ?>
    </div>
</div>
<script>
    function submitForm(){
        $('#aanmelden').submit();
    }
</script>