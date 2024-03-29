<?php get_header();
/** @var EP_Competition $competition */
$competition = $GLOBALS['ep_competition'];
$lastFixtures = $nextFixtures = array();

if ($competition->getStage()>EP_Competition::BEFORE_KICK_OFF) {
    $lastFixtures = $competition->getLastFixtures(3);
}
if ($competition->getStage()<EP_Competition::AFTER_FINAL_GAME) {
	$nextFixtures = $competition->getNextFixtures(3);
}
?>

    <section class="slider">
        <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner">
                <?php $j=rand(1,9); ?>
                <div class="item active"> <img data-src="<?php echo get_stylesheet_directory_uri() ?>/images/slider/slider0<?php echo $j ?>.jpg" alt="slide" src="<?php echo get_stylesheet_directory_uri() ?>/images/slider/slider0<?php echo $j ?>.jpg">
                    <div class="container hero">
                        <div class="carousel-caption text-left">
                            <?php if ($competition->getStage()==EP_Competition::BEFORE_KICK_OFF) {
	                            $time = $competition->getTimeToStart();
                                $days_left_text =
                                    sprintf(__('Quedan %s días','enroporra'),'<span class="days">'.$time->days.'</span>').',&nbsp;
                                    <span class="hours">'.$time->hours.'</span>:<span class="minutes">'.$time->minutes.'</span>:<span class="seconds">'.$time->seconds.'</span>
                                    <p><a class="btn btn-default btn-lg" href="/apuesta" role="button">'.__('¡Haz tu apuesta!','enroporra').'</a></p>
                                    <p class="subtitle">'.__('Se admiten apuestas hasta media hora antes del comienzo del partido inaugural','enroporra').'</p>';
                                ?>
                                <div id="timeToGo">
                                    <?php echo $days_left_text ?>
                                </div>
                            <?php } else if ($competition->getStage() < EP_Competition::AFTER_FINAL_GAME && count($nextFixtures)) { ?>
                                <div id="timeToGo"><?php _e('Próximos partidos','enroporra') ?></div>
                            <?php } ?>
                                <div class="scores">
                            <?php
                                foreach ($nextFixtures as $fixture) fixtureHTML($fixture);
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="main__middle__container homepage">
        <div class="row  text-left headings no-margin nothing">
            <?php if ($competition->getStage()==EP_Competition::BEFORE_KICK_OFF) { ?>
            <div id="timeToGoMobile">
		        <?php echo $days_left_text ?>
            </div>
            <?php } else if ($competition->getStage()<EP_Competition::AFTER_FINAL_GAME && $nextFixtures) { ?>
            <div class="container scores-mobile">
                <div class="seper"></div>
                <p class="page_title"><?php _e('Próximos partidos','enroporra') ?></p>
                <?php foreach ($nextFixtures as $fixture) fixtureHTML($fixture); ?>
            </div>
            <?php  } ?>
            <?php if ($lastFixtures) { ?>
                <div class="container">
                    <div class="seper"></div>
                    <p class="page_title"><?php _e('Últimos partidos','enroporra') ?></p>
                    <div style="display:flex; flex-wrap: wrap; justify-content: center">
			            <?php foreach ($lastFixtures as $fixture) fixtureHTML($fixture); ?>
                    </div>
                </div>
            <?php  } ?>
            <div class="container">
                <div class="seper"></div>
                <h1 class="page_title">Rellena tu segunda fase, <span>hay poco tiempo</span>.</h1>
                <p>Tienes hasta el sábado 3 a las 15:30 (hora peninsular española) para rellenar la <a class="btn btn-primary btn-lg" href="/mis-apuestas/" role="button">segunda fase</a></p>.</p>
                <p>Os recordamos nuestros canales de comunicación, el email <a href="mailto:comisionporra@gmail.com">comisionporra@gmail.com</a> y nuestra cuenta de Twitter <a href="https://www.twitter.com/comisionporra" target="_blank">@Comisionporra</a></p>

                <p>Muchas gracias por estar ahí, un año más.<br />La comisión.</p>
                <!--<p><a class="btn btn-primary btn-lg" href="#" role="button">more info</a></p>-->
            </div>
        </div>
        <div class="titles services text-left">
            <div class="container">
                <div class="seper"></div>
                <h2 class="page__title">Enroporra en Twitter</h2>
                <p class="small-paragraph">¡Hacemos RT de la gente simpática!</p>
            </div>
        </div>
        <div class="row three__blocks  text-left no_padding no-margin">
            <div class="container">
                <a class="twitter-timeline" data-lang="es" data-height="1200" href="https://twitter.com/comisionporra?ref_src=twsrc%5Etfw">Tweets by comisionporra</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
        </div>
        <!--<div class="row three__blocks  text-left no_padding no-margin">
            <div class="container">
                <div class="col-md-4">
                    <h3><a href="#">Modern Design</a><span class="small-paragraph">Maecenas fermentum semper</span></h3>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/content__images/img1.jpg" alt="image" class="img-responsive img-rounded">
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. </p>
                    <p><a class="btn btn-default btn-md" href="#" role="button">Learn more</a>
                </div>
                <div class="col-md-4 middle">
                    <h3><a href="#">High Quality</a><span class="small-paragraph">Maecenas fermentum semper</span></h3>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/content__images/img2.jpg" alt="image" class="img-responsive img-rounded">
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. </p>
                    <p><a class="btn btn-default btn-md" href="#" role="button">Learn more</a>
                </div>
                <div class="col-md-4">
                    <h3><a href="#">Quick Support</a><span class="small-paragraph">Maecenas fermentum semper</span></h3>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/content__images/img3.jpg" alt="image" class="img-responsive img-rounded">
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. </p>
                    <p><a class="btn btn-default btn-md" href="#" role="button">Learn more</a>
                </div>
            </div>
        </div>
        <div class="row no_padding no-margin nothing nice__title text-left">
            <div class="container">
                <div class="seper"></div>
                <h2 class="page__title">Donec fringilla vitae ligula  facilisis. </h2>
                <p>Phasellus laoreet massa id justo mattis pharetra. Fusce suscipit ligula vel quam viverra sit amet mollis tortor congue. Sed quis mauris sit amet magna accumsan tristique.</p>
            </div>
        </div>
        <div class="titles aboutus text-left">
            <div class="container">
                <div class="seper"></div>
                <h2 class="page__title">about us</h2>
                <p class="small-paragraph">Cras iaculis ultricies nulla.</p>
            </div>
        </div>
        <div class="row grey-info-block text-left">
            <div class="container">
                <div class="col-md-6">
                    <h3><a href="#">Commodo id natoque malesuada sollicitudin</a></h3>
                    <p class="small-paragraph">Cras iaculis ultricies nulla.</p>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/content__images/pic1.jpg" alt="pic" class="img-rounded img-responsive" id="picture">
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>
                    <p><a class="btn btn-info" href="#" role="button">Learn more</a></p>
                </div>
                <div class="col-md-6">
                    <h3><a href="#">Commodo id natoque malesuada sollicitudin</a></h3>
                    <p class="small-paragraph">Cras iaculis ultricies nulla.</p>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/content__images/pic2.jpg" alt="pic" class="img-rounded img-responsive" id="picture">
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>
                    <p><a class="btn btn-info" href="#" role="button">Learn more</a></p>
                </div>
            </div>
        </div>
        <div class="row text-left nothing line__bg testimonials">
            <div class="container">
                <h3 class="subtitle">Aliquam porttitor mauris sit amet quisque volutpat.</h3>
                <p class="small-paragrapher">Maecenas fermentum semper porta.</p>
                <img src="<?php echo get_stylesheet_directory_uri() ?>/images/content__images/big_image.jpg" class="img-responsive img-rounded">
                <p style="color:#fff;">Donec fringilla vitae ligula vitae facilisis. Vivamus consectetur tincidunt lorem a bibendum. Sed fermentum ac ante sit nec amet convallis suspendisse potenti suspendisse mollis. Tortor nec iaculis tempor, orci augue dignissim nibh, id modo risus purus at nibh. Curabitur ac urna felis phasellus onvallis leo orci, sit amet feugiat nunc pretium fringilla. Morbi nec nulla a magna porta ullamcorper eget sit amet metus duis varius est ut lectus malesuada tincidunt vitae ut velit ac nunc.Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>
            </div>
        </div>-->
    </section>
<?php
get_footer();
