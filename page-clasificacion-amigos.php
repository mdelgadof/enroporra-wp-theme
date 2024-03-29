<?php
    /**
     * @var EP_Competition $competition
     */
    $competition = $GLOBALS['ep_competition'];
    $betsTableAll = $competition->getTableBet();
    $betsTable = array();
    $WPuser = wp_get_current_user();
    if ($WPuser->ID) {
        $user = new EP_User($WPuser->ID);
        $friendsBets = $user->getBetFriendsIds();
        $userBets = $user->getBets($competition,EP_User::INTEGER);
        foreach ($betsTableAll as $bet) {
            if (in_array($bet["bet"]->getId(),$friendsBets) || in_array($bet["bet"]->getId(),$userBets))
                $betsTable[] = $bet;
        }
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
                <h2 class="page__title"><?php _e('clasificación de mis amigos','enroporra') ?></h2>
                <p class="small-paragraph"><?php (time()%2==0) ? _e('"Juego para ser feliz, no para ganar nada" (Andrés Iniesta)','enroporra') : _e('"Ganar, eso es lo más importante para mí. Es tan simple como eso" (Cristiano Ronaldo)','enroporra'); ?></p>
            </div>
        </div>
        <?php if (!is_user_logged_in()) {
            global $wp;
            loginFormHTML(home_url( $wp->request ));
        } else { ?>
        <div class="row text-left no-margin nothing">
            <div class="container container-ranking black-text">
	            <?php rankingHTML($competition, $betsTable, $userBets); ?>
            </div>
        </div>
        <?php } ?>
    </section>
<?php
get_footer();
