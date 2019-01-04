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
        <?php if(isset($inschrijvingen) && $event->inschrijfsysteem) { ?>
            <h4><?= lang('agenda_recent')?></h4>
            <table style="width: 100%">
                <?php $i = 0; foreach ($inschrijvingen as $inschrijving) { $i++; if ($i <= 5) { ?>
                    <tr>
                        <td><?=$inschrijving->naam?></td>
                        <?php if ($inschrijving->opmerking !== "") { ?> <td><?=$inschrijving->opmerking?></td> <?php } ?>
                    </tr>
                <?php } else { ?>
                    <tr class="inschrijving hidden">
                        <td><?=$inschrijving->naam?></td>
                        <?php if ($inschrijving->opmerking !== "") { ?> <td><?=$inschrijving->opmerking?></td> <?php } ?>
                    </tr>
                <?php }}  ?>
            </table>
            <?php if ($i > 6) { ?>
                <a class="show_all" onclick="showAll()"><?= lang('agenda_show_registrations')?></a>
                <a class="hide_all hidden" onclick="hideAll()"><?= lang('agenda_hide_registrations')?></a>
            <?php }?>
        <?php } elseif ($event->inschrijfsysteem) { ?>
            <br><br>
            <table style="width: 100%;">
                <tr>
                    <td><?= lang('agenda_no_registrations')?></td>
                </tr>
            </table>
        <?php } else { ?>
            <br><br>
            <table style="width: 100%">
                <tr>
                    <td><?= lang('agenda_no_registrations_needed') ?></td>
                </tr>
            </table>
        <?php } ?>
    </div>
    <div class="col-sm-6" style="padding-left: 0">
        <h4>Details</h4>
        <table style="width:100%;">
            <tr>
                <td><b><?= lang('agenda_from') ?></b></td>
                <td><?= date_format(date_create($event->van), 'd-m-Y H:i')?></td>
            </tr>
            <tr>
                <td><b><?= lang('agenda_until') ?></b></td>
                <td><?= date_format(date_create($event->tot), 'd-m-Y H:i') ?></td>
            </tr>
            <tr>
                <td><b><?= lang('agenda_location') ;?></b></td>
                <td><?= $event->locatie?></td>
            </tr>
            <?php if ($event->inschrijfsysteem) { ?>
                <tr>
                    <td><b><?= lang('agenda_registration_deadline') ?></b></td>
                    <td><?= date_format(date_create($event->inschrijfdeadline), 'd-m-Y H:i') ?></td>
                </tr>
                <tr>
                    <td><b><?= lang('agenda_cancelation_deadline')?></b></td>
                    <td><?=date_format(date_create($event->afmelddeadline), 'd-m-Y H:i')?></td>
                </tr>
                <?php if ($event->maximum > 0) { ?>
                    <tr>
                        <td><b><?= lang('agenda_nr_maximum')?></b></td>
                        <td><?= $aantal_aanmeldingen . '/' . $event->maximum ?></td>
                    </tr>
            <?php } else { ?>
		            <tr>
			            <td><b><?= lang('agenda_nr_maximum')?></b></td>
			            <td><?= $aantal_aanmeldingen ?></td>
		            </tr>
	        <?php }} ?>
        </table>
    </div>
    <div class="col-sm-12 no_padding margin_10_top">
<?php if ($event->inschrijfsysteem) { ?>
        <?= form_open(($event->soort === 'nszk') ? '/agenda/nszk' : '/agenda/aanmelden', array("id" => "aanmelden", "name" => "aanmelden")); ?>
        <input type="hidden" value="<?=$event->event_id?>" name="event_id"/>
        <input type="hidden" value="<?=$event->soort?>" name="event_soort">
    <?php if (! $aangemeld && date('Y-m-d H:i:s') <= $event->inschrijfdeadline) {
    	    if ( $event->maximum == 0 || $aantal_aanmeldingen < $event->maximum) {
                if ($event->soort === "nszk"){
                    $slagen = json_decode($event->slagen);
                    foreach ($slagen as $slag) { ?>
                        <div class="form-group">
                            <div class="col-sm-4 no_padding">
                                <label><?=$slag?></label>
                            </div>
                            <div class="col-sm-8 no_padding">
                                <input type="hidden" value="<?= $slag?>" name="slag[]">
                                <input type="text" class="form-control" name="tijd[]" placeholder="Tijd"/>
                            </div>
                        </div>
                    <?php }
                } ?>
                <div class="form-group no_padding">
                    <input type="text" name="opmerking" maxlength="20" class="form-control" style="margin-top: 20px" placeholder="<?= lang("agenda_remark"); ?>">
                    <?php if ($event->betalen) { ?>
                        <input type="checkbox" required> <?= lang('agenda_agree_terms') ?>
                    <?php } ?>
                </div>
                <div class="form-group">
                        <button type="submit" class="btn btn-primary form-control"><?= lang('agenda_register') ?></button>
                </div>
            <?php }
            else { ?>
                <div class="alert alert-warning">
                    <strong><?= lang('agenda_full'); ?></strong>
                </div>
            <?php } ?>
	<?php } elseif ( ! $aangemeld ) { ?>
		<div class="alert alert-warning">
			<strong><?= lang('agenda_no_registration'); ?></strong>
		</div>
	<?php } elseif (date('Y-m-d H:i:s') <= $event->afmelddeadline) { ?>
        <div class="form-group">
            <a href="/agenda/afmelden/<?= $event->event_id?>" class="btn btn-primary center-block"><?= lang('agenda_cancel'); ?></a>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning">
            <strong><?= lang('agenda_no_cancel'); ?></strong>
        </div>
    <?php } ?>
<?php } echo form_close();
        if ($registration_details) { ?>
            <a type="button" class="btn btn-warning form-control" href="/agenda/edit_details/<?= $event->event_id ?>"><?= lang('agenda_change_registration')?></a>
        <?php } ?>
    </div>
</div>
<script>
    function submitForm(){
        $('#aanmelden').submit();
    }
</script>