<?php

function generate_icon($name)
{
    switch (strtolower($name)) {
        case 'home':
            return 'fa fa-home';
            break;
        case 'zwemmen':
            return 'fa fa-swimmer';
            break;
        case 'waterpolo':
            return 'fa fa-volleyball-ball';
            break;
        case 'vereniging':
            return 'fa fa-users';
            break;
        default:
            return 'fa fa-phone';
            break;
    }
}

?>
<div class="banner">
    <div class="header">
        <nav class="navbar navbar-expand-xl navbar-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#hydrofiel-nav" aria-controls="hydrofiel-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="hydrofiel-nav">
                <ul class="navbar-nav">
                <?php foreach ($hoofdmenus as $hoofdmenu) {
                        if ($hoofdmenu['submenu'] !== null) { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
                                    <span class="<?= generate_icon( $hoofdmenu['naam'] ) ?>"></span>
                                    <?= $engels ? $hoofdmenu['engelse_naam'] : $hoofdmenu['naam'] ?>
                                    <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu hydrofiel-dropdown">
                                    <a class="dropdown-item" href="/page/id/<?= $hoofdmenu['id'] ?>"><?= $engels ? $hoofdmenu['engelse_naam'] : $hoofdmenu['naam'] ?></a>
                                    <?php foreach ($hoofdmenu['submenu'] as $submenu) {
                                        if (!$submenu['ingelogd'] || $submenu['ingelogd'] == $logged_in) { ?>
                                                <a class="dropdown-item" href="/page/id/<?= $submenu['id'] ?>"><?= $engels ? $submenu['engelse_naam'] : $submenu['naam'] ?></a>
                                        <?php }
                                    } ?>
                                </div>
                            </li>
                            <?php
                        } else { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/page/id/<?= $hoofdmenu['id'] ?>">
                                    <span class="<?= generate_icon( $hoofdmenu['naam'] ) ?>"></span>
                                    <?= $engels ? $hoofdmenu['engelse_naam'] : $hoofdmenu['naam'] ?>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    if ($logged_in) { ?>
                        <li class="nav-item"><a href="/agenda" class="nav-link"><span
                                        class="fa fa-calendar"></span> <?= lang('general_calendar') ?></a></li>
                        <li class="nav-item"><a href="/profile" class="nav-link"><span
                                        class="fa fa-user"></span> <?= lang('general_profile') ?> </a></li>
                        <?php if ($superuser) { ?>
                            <li class="nav-item"><a href="/beheer" class="nav-link"><span
                                            class="fa fa-cogs"></span> <?= lang('general_control_panel') ?></a></li>
                        <?php } ?>
                        <li class="nav-item"><a href="/inloggen" class="nav-link"><span
                                        class="fa fa-sign-out"></span> <?= lang('general_sign_out') ?> </a></li>
                        <?php
                    } else { ?>
                        <li class="nav-item"><a href="/inloggen" class="nav-link"><span
                                        class="fa fa-sign-in-alt"></span> <?= lang('general_sign_in') ?> </a></li>
                        <?php
                    } ?>
                    <li class="nav-item"><a href="/home/language" class="nav-link"><span
                                    class="fa fa-flag"></span> <?= lang('general_language') ?></a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="banner-info" id="info">
        <p><?= lang('general_info') ?></p>
        <!--        <label></label>-->
        <h2>N.S.Z.&W.V. Hydrofiel</h2>
    </div>
</div>
<div class="container pt-3">
    <?php if ($this->session->success) { ?>
        <div class="alert alert-success">
            <strong><?= $this->session->success ?></strong>
        </div>
    <?php } elseif ($this->session->error) { ?>
        <div class="alert alert-danger">
            <strong><?= $this->session->error ?></strong>
        </div>
    <?php } ?>
