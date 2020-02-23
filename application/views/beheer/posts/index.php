<table class="table table-responsive table-striped">
    <thead>
    <tr>
        <th>Titel</th>
        <th>Datum</th>
        <th>Beheer</th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($posts)) {
        foreach ($posts as $post) { ?>
            <tr>
                <td><?= $post->post_title_nl ?></td>
                <td><?= date_format(date_create($post->post_timestamp), 'd-m-Y H:i') ?></td>
                <td>
                    <a onclick="showModal('<?= $post->post_title_nl ?>', <?= $post->post_id ?>)"><span
                                class="fa fa-trash"></span></a>
                </td>
            </tr>
        <?php }
    } ?>
    </tbody>
</table>
<hr>
<h3>Post toevoegen</h3>
<?= form_open('/beheer/posts/add_post'); ?>
<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label" for="post_title_nl">Titel (NL)</label>
    </div>
    <div class="col-sm-10">
        <input class="form-control" name="post_title_nl" id="post_title_nl" maxlength="175">
    </div>
</div>
<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label" for="summernote">Tekst (NL)</label>
    </div>
    <div class="col-sm-10">
        <textarea id="summernote" name="post_text_nl" id="post_text_nl"></textarea>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label" for="post_title_en">Titel (EN)</label>
    </div>
    <div class="col-sm-10">
        <input class="form-control" name="post_title_en" id="post_title_en" maxlength="175">
    </div>
</div>
<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label" for="engels">Tekst (EN)</label>
    </div>
    <div class="col-sm-10">
        <textarea class="input-block-level" id="engels" name="post_text_en"></textarea>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label" for="post_image">Plaatje</label>
    </div>
    <div class="col-sm-10">
        <input class="form-control" name="post_image" id="post_image" maxlength="255"><br>
        <button class="btn btn-primary" type="submit">Post toevoegen</button>
    </div>
</div>
<?= form_close(); ?>
<script>
    function showModal(naam, post_id) {
        showBSModal({
            title: "Weet je het zeker?",
            body: "De post '" + naam + "' zal verwijderd worden! ",
            actions: [{
                label: "Ja",
                cssClass: "btn-danger",
                onClick: function (e) {
                    window.location.replace("/beheer/posts/delete_post/" + post_id);
                },
            }, {
                label: "Nee",
                cssClass: "btn-success",
                onClick: function (e) {
                    $(e.target).parents(".modal").modal("hide");
                },
            }],
        });
    }
</script>
