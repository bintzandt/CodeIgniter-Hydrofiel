<!-- A wrapper to easily create bootstrap modals from JS -->
<script src="/assets/bootstrap-model-wrapper.js"></script>

<!-- Dependencies for bootstrap-datetimepicker -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.24.0/moment.min.js"></script>
<script src="/assets/moment.locale.nl.js"></script>

<!-- flatpickr dependencies -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!--  Default HTML does not contain a multiselect, include one -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@0.9.15/dist/js/bootstrap-multiselect.min.js"
            integrity="sha256-NNTJMfCjKMElj34Oh2XgoYhoaN6UzMjeTtEXo2c2TZc=" crossorigin="anonymous"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.15/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.15/dist/summernote.min.js"></script>

<script src="/assets/hydrofiel-admin.js"></script>

<div class="banner">
    <div class="header">
        <nav class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/beheer">Pagina</a></li>
                    <li><a href="/beheer/posts">Posts</a></li>
                    <li><a href="/beheer/upload">Uploaden</a></li>
                    <li><a href="/beheer/agenda">Agenda</a></li>
                    <li><a href="/beheer/mail">Mail</a></li>
                    <li><a href="/beheer/leden">Leden</a></li>
                    <li><a href="">Terug</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="banner-info" id="info">
        <p>Zwem & Waterpolovereniging</p>
        <label></label>
        <h2>N.S.Z.&W.V. Hydrofiel</h2>
    </div>
</div>
<div class="container" style="padding-top: 10px; padding-bottom: 10px">
    <?php if ($this->session->success) { ?>
        <div class="alert alert-success">
            <strong><?= $this->session->success ?></strong>
        </div>
    <?php } elseif ($this->session->error) { ?>
        <div class="alert alert-danger">
            <strong><?= $this->session->error ?></strong>
        </div>
    <?php } ?>
