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
        <nav class="navbar navbar-expand-xl navbar-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#hydrofiel-nav" aria-controls="hydrofiel-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="hydrofiel-nav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="/beheer" class="nav-link">Pagina</a>
                    </li>
                    <li class="nav-item">
                        <a href="/beheer/posts" class="nav-link">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a href="/beheer/upload" class="nav-link">Uploaden</a>
                    </li>
                    <li class="nav-item">
                        <a href="/beheer/agenda" class="nav-link">Agenda</a>
                    </li>
                    <li class="nav-item">
                        <a href="/beheer/mail" class="nav-link">Mail</a>
                    </li>
                    <li class="nav-item">
                        <a href="/beheer/leden" class="nav-link">Leden</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Terug</a>
                    </li>
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
<div class="container ptb-3">
    <?php if ($this->session->success) { ?>
        <div class="alert alert-success">
            <strong><?= $this->session->success ?></strong>
        </div>
    <?php } elseif ($this->session->error) { ?>
        <div class="alert alert-danger">
            <strong><?= $this->session->error ?></strong>
        </div>
    <?php } ?>
