<?php
/** @var EP_Competition $competition */
$competition = $GLOBALS['ep_competition'];
$_locale_map  = ['en_US' => 'en', 'fr_FR' => 'fr'];
$_html_lang   = $_locale_map[determine_locale()] ?? 'es';
$_ui_locale   = (isset($_COOKIE['ep_locale']) && in_array($_COOKIE['ep_locale'], ['en_US', 'fr_FR'], true))
    ? $_COOKIE['ep_locale'] : 'es_ES';
$_flags_url   = content_url('plugins/enroporra/images/flags/');
?><!DOCTYPE html>
<html lang="<?= $_html_lang ?>">
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
                    <li <?php if ($competition->beforeStart()) { ?>class="dropdown"<?php } ?>><a href="<?php echo $competition->beforeStart() ? "#":"/clasificacion" ?>"><?php _e('Clasificación','enroporra') ?></a>
                        <?php if ($competition->beforeStart()) { ?>
                            <ul>
                                <li><a href="#"><?php _e('La competición no ha comenzado','enroporra') ?></a></li>
                            </ul>
                        <?php } ?>
                    </li>
                    <li class="dropdown"><a href="#"><?php _e('Enlaces oficiales UEFA/FIFA','enroporra') ?></a>
                        <ul>
                            <li><a href="<?php echo $competition->getOfficialSite() ?>" target="_blank"><?php _e('Sitio oficial','enroporra') ?></a></li>
                            <li><a href="<?php echo $competition->getMatchCalendarSite() ?>" target="_blank"><?php _e('Calendario de partidos','enroporra') ?></a></li>
                            <li><a href="<?php echo $competition->getTopScorersSite() ?>" target="_blank"><?php _e('Goleadores','enroporra') ?></a></li>
                        </ul>
                    <li><a href="mailto:<?php echo $competition->getEmail() ?>"><?php _e('Escríbenos','enroporra') ?></a></li>
                    <li class="lang-switcher">
                        <a href="#" onclick="epSetLocale('es_ES');return false;" title="Español"><img src="<?= $_flags_url ?>es.png" class="lang-flag<?= $_ui_locale==='es_ES'?' active':'' ?>" alt="ES"></a>
                        <a href="#" onclick="epSetLocale('en_US');return false;" title="English"><img src="<?= $_flags_url ?>us.png" class="lang-flag<?= $_ui_locale==='en_US'?' active':'' ?>" alt="EN"></a>
                        <a href="#" onclick="epSetLocale('fr_FR');return false;" title="Français"><img src="<?= $_flags_url ?>fr.png" class="lang-flag<?= $_ui_locale==='fr_FR'?' active':'' ?>" alt="FR"></a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
    </div>
</header>