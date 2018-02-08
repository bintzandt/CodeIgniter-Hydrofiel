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
                                <li><a href="/page/id/<?=$hoofdmenu['id']?>"><?= $engels? $hoofdmenu['engelse_naam']:$hoofdmenu['naam'] ?></a></li>
                            <?php
                            }
                        }
                        if ($logged_in) { ?>
                            <li><a href="/agenda"><span class="fa fa-calendar"></span> Agenda</a></li>
                            <li><a href="/profile"><span class="fa fa-user"></span> Profiel </a></li>
                            <?php if ($superuser) { ?>
                                <li><a href="/beheer"><span class="fa fa-gears"></span> Beheerpanel </a></li>
                            <?php } ?>
                            <li><a href="/inloggen" style="margin-bottom: 25px"><span class="fa fa-sign-out"></span> Uitloggen </a></li>
                        <?php
                        }
                        else { ?>
                            <li><a href="/inloggen"><span class="fa fa-sign-in"></span> Inloggen </a></li>
                        <?php
                        }
                        if ($engels) { ?>
                            <li><a href="/home/language" style="margin-bottom: 25px"><span class="fa fa-flag"></span> Nederlands</a></li>
                        <?php } else { ?>
                            <li><a href="/home/language" style="margin-bottom: 25px"><span class="fa fa-flag"></span> English</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
        </div>
    <div class="banner-info clickable-row" id="info" data-href="/">
        <p>Zwem & Waterpolovereniging</p>
<!--        <label></label>-->
        <h2>N.S.Z.&W.V. Hydrofiel</h2>
    </div>
</div>
<div class="container" style="padding-top: 10px; padding-bottom: 10px">