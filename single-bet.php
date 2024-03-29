<?php

$bet = new EP_Bet(get_the_ID());


get_header();
?>
    <section class="main__middle__container first-section">
        <div class="titles aboutus text-left">
            <div class="container">
                <div class="seper"></div>
                <h2 class="page__title"><?php echo $bet->getName().", ".$bet->getCompetition()->getName() ?></h2>
                <p class="small-paragraph"><?php (time()%2==0) ? _e('"Juego para ser feliz, no para ganar nada" (Andrés Iniesta)','enroporra') : _e('"Ganar, eso es lo más importante para mí. Es tan simple como eso" (Cristiano Ronaldo)','enroporra'); ?></p>
            </div>
        </div>
        <div class="row text-left no-margin nothing">
            <div class="container black-text">
				<?php echo $bet->getHTMLBet(true); ?>
            </div>
        </div>
    </section>
<?php
get_footer();
