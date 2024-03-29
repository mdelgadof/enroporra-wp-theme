<?php
    /**
     * @var EP_Competition $competition
     */
    $competition = (object) $GLOBALS['ep_competition'];
    $betsTable = $competition->getTableBet();
    $WPuser = wp_get_current_user();
    if ($WPuser->ID) {
        $user = new EP_User($WPuser->ID);
        $friendsBets = $user->getBetFriendsIds();
        $userBets = $user->getBets($competition,EP_User::INTEGER);
    }
    else {
        $friendsBets = $userBets = array();
    }

	get_header();

	?>

<section class="main__middle__container first-section">
	<div class="titles aboutus text-left">
		<div class="container">
			<div class="seper"></div>
			<h2 class="page__title"><?php _e('clasificación','enroporra') ?></h2>
			<p class="small-paragraph"><?php (time()%2==0) ? _e('"Juego para ser feliz, no para ganar nada" (Andrés Iniesta)','enroporra') : _e('"Ganar, eso es lo más importante para mí. Es tan simple como eso" (Cristiano Ronaldo)','enroporra'); ?></p>
		</div>
	</div>
	<div class="row text-left no-margin nothing">
		<div class="container container-ranking black-text">
            <?php rankingHTML($competition, $betsTable, $userBets,  $friendsBets); ?>
        </div>
    </div>
</section>

<?php

get_footer();