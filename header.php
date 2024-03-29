<?php
/** @var EP_Competition $competition */
$competition = $GLOBALS['ep_competition'] ?><!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroporra <?php echo $competition->getName() ?></title>
    <?php wp_head() ?>
</head>
<body>
<header class="main__header">
    <div class="container">
        <nav class="navbar navbar-default" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="navbar-header">
                <h1 class="navbar-brand"><a href="/">enro<span>porra</span></a></h1>
                <a href="#" onClick="javascript.void()" class="submenu">Menus</a> </div>
            <div class="menuBar">
                <ul class="menu">
                    <li <?php if (is_front_page()) { ?>class="active"<?php } ?>><a href="/">Home</a></li>
                    <li><a href="<?php echo $competition->getRulesUrl() ?>" download=""><?php _e('Bases','enroporra') ?></a> </li>
                    <li><a href="/mis-apuestas"><?php _e('Mis apuestas','enroporra') ?></a></li>
                    <li class="dropdown"><a href="#"><?php _e('Mis amigos','enroporra') ?></a>
	                <?php if ($competition->beforeStart()) { ?>
                        <ul>
                            <li><a href="#"><?php _e('La competición no ha comenzado','enroporra') ?></a></li>
                        </ul>
	                <?php } else { ?>
                        <ul>
                            <li><a href="/amigos/"><?php _e('Elegir mis amigos','enroporra') ?></a></li>
                            <li><a href="/clasificacion-amigos/"><?php _e('Clasificación de mis amigos','enroporra') ?></a></li>
                        </ul>
                    <?php } ?>
                    </li>
                    <li <?php if ($competition->beforeStart()) { ?>class="dropdown"<?php } ?>><a href="<?php echo $competition->beforeStart() ? "#":"/clasificacion" ?>">Clasificación</a>
                        <?php if ($competition->beforeStart()) { ?>
                            <ul>
                                <li><a href="#">La competición no ha comenzado</a></li>
                            </ul>
                        <?php } ?>
                    </li>
                    <li class="dropdown"><a href="#">Enlaces oficiales UEFA/FIFA</a>
                        <ul>
                            <li><a href="<?php echo $competition->getOfficialSite() ?>" target="_blank">Sitio oficial</a></li>
                            <li><a href="<?php echo $competition->getMatchCalendarSite() ?>" target="_blank">Calendario de partidos</a></li>
                            <li><a href="<?php echo $competition->getTopScorersSite() ?>" target="_blank">Goleadores</a></li>
                        </ul>
                    <li><a href="mailto:<?php echo $competition->getEmail() ?>">Escríbenos</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
    </div>
</header>