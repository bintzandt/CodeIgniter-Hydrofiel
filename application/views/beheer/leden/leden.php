<div align="right" style="padding: 20px"><a href="/beheer/leden/importeren"><b>Leden importeren</b></a></div>
<div class="container-fluid">
    <div class="col-lg-6">
        <h2>Zwemmers</h2>
        <table class="table table-sm table-striped">
            <thead>
            <th>Naam</th>
            <th>Email</th>
            </thead>
            <tbody>
            <?php foreach ($leden as $lid) {
                if ($lid->lidmaatschap === "zwemmer") { ?>
                    <tr class="clickable-row" data-href="/profile/id/<?= $lid->id ?>">
                        <td><?= $lid->naam ?></td>
                        <td><a href="mailto:<?= $lid->email ?>"><?= $lid->email ?></a></td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>
    </div>
    <div class="col-lg-6">
        <h2>Waterpolo</h2>
        <table class="table table-sm table-striped table-hover">
            <thead>
            <th>Naam</th>
            <th>Email</th>
            </thead>
            <tbody>
            <?php foreach ($leden as $lid) {
                if ($lid->lidmaatschap === "waterpolo_competitie" || $lid->lidmaatschap === "waterpolo_recreatief") { ?>
                    <tr class="clickable-row" data-href="/profile/id/<?= $lid->id ?>">
                        <td><?= $lid->naam ?></td>
                        <td><a href="mailto:<?= $lid->email ?>"><?= $lid->email ?></a></td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>
    </div>
</div>
