<div align="right"><a href="<?=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/'?>">Terug</a></div>
<div class="container">
        <div class="col-xs-12">
        <p>
            <b>Bericht verstuurd op: </b><?=date_format(date_create($datum), 'd-m-Y')?><b> vanuit het emailadres: </b><?=$van?><b> met als onderwerp: </b><?= $onderwerp?>
        </p>
        <div align="center">
            <?=$bericht?>
        </div>
    </div>
</div>