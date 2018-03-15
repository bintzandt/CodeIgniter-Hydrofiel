<script>
    $(document).on('click',function(){
        $('.navbar-collapse').collapse('hide');
    });
</script>
<div class="banner">
        <div class="header">
            <nav class="navbar navbar-default">
                <div class="navbar-header">
                    <button style="margin-left: 0.2em" type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <?php foreach ($hoofdmenus as $hoofdmenu) { ?>
                            <?php
                            if ($hoofdmenu['submenu'] !== null){ ?>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown"><?= $engels? $hoofdmenu['engelse_naam']:$hoofdmenu['naam'] ?><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/page/id/<?=$hoofdmenu['id']?>"><?= $engels?$hoofdmenu['engelse_naam']:$hoofdmenu['naam'] ?></a></li>
                                        <?php foreach ($hoofdmenu['submenu'] as $submenu){ if (!$submenu['ingelogd'] || $submenu['ingelogd'] == $logged_in) { ?>
                                            <li><a href="/page/id/<?=$submenu['id']?>"><?= $engels?$submenu['engelse_naam']:$submenu['naam'] ?></a></li>
                                        <?php }} ?>
                                    </ul>
                                </li>
                            <?php
                            }
                            else { ?>
                                <li class="dropdown"><a class="dropdown-toggle" href="/page/id/<?=$hoofdmenu['id']?>"><?= $engels? $hoofdmenu['engelse_naam']:$hoofdmenu['naam'] ?></a></li>
                            <?php
                            }
                        }
                        if ($logged_in) { ?>
                            <li class="dropdown"><a href="/agenda" class="dropdown-toggle"><span class="fa fa-calendar"></span> <?= lang('general_calendar')?></a></li>
                            <li class="dropdown"><a href="/profile" class="dropdown-toggle"><span class="fa fa-user"></span> <?= lang('general_profile')?> </a></li>
                            <?php if ($superuser) { ?>
                                <li class="dropdown"><a href="/beheer" class="dropdown-toggle"><span class="fa fa-gears"></span> <?= lang('general_control_panel')?></a></li>
                            <?php } ?>
                            <li class="dropdown"><a href="/inloggen" class="dropdown-toggle"><span class="fa fa-sign-out"></span> <?= lang('general_sign_out')?> </a></li>
                        <?php
                        }
                        else { ?>
                            <li class="dropdown"><a href="/inloggen" class="dropdown-toggle"><span class="fa fa-sign-in"></span> <?= lang('general_sign_in')?> </a></li>
                        <?php
                        } ?>
                        <li class="dropdown"><a href="/home/language" class="dropdown-toggle"><span class="fa fa-flag"></span> <?= lang('general_language') ?></a></li>
                    </ul>
                </div>
            </nav>
        </div>
    <div class="banner-info clickable-row" id="info" data-href="/">
        <p><?= lang('general_info') ?></p>
<!--        <label></label>-->
        <h2>N.S.Z.&W.V. Hydrofiel</h2>
    </div>
</div>
<div class="container" style="padding-top: 10px; padding-bottom: 10px">