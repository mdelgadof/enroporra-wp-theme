<?php
    /**
     * @var EP_Competition $competition
     */
    $competition = $GLOBALS['ep_competition'];
    $rankingCache = get_option('ep_ranking_' . $competition->getId());
    $WPuser = wp_get_current_user();
    if ($WPuser->ID) {
        $user = new EP_User($WPuser->ID);
        $friendsBets = $user->getBetFriendsIds();
        $userBets = $user->getBets($competition, EP_User::INTEGER);
        if ($rankingCache) {
            $filteredRows = array_values(array_filter($rankingCache['rows'], function($row) use ($friendsBets, $userBets) {
                return in_array($row['id'], $friendsBets) || in_array($row['id'], $userBets);
            }));
            $friendsCache = array_merge($rankingCache, ['rows' => $filteredRows]);
        } else {
            $betsTableAll = $competition->getTableBet();
            $betsTable    = array_values(array_filter($betsTableAll, fn($b) =>
                in_array($b['bet']->getId(), $friendsBets) || in_array($b['bet']->getId(), $userBets)
            ));
        }
    } else {
        $friendsBets = $userBets = [];
        $friendsCache = null;
        $betsTable = [];
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
        <?php if (time() < strtotime('2026-06-28 18:30:00 UTC')): ?>
        <div id="playoff-overlay-wrapper" class="row text-left no-margin nothing">
            <div class="container black-text">
                <?php include get_template_directory() . '/templates/playoff-overlay.php'; ?>
            </div>
        </div>
        <div id="ranking-content" style="display:none">
            <?php if (!is_user_logged_in()) {
                global $wp;
                loginFormHTML(home_url( $wp->request ));
            } else { ?>
            <div class="row text-left no-margin nothing">
                <div class="container container-ranking black-text">
                    <?php if ($rankingCache) {
                        rankingHTMLCached($competition, $friendsCache, $userBets);
                    } else {
                        rankingHTML($competition, $betsTable, $userBets);
                    } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <script>
        function dismissPlayoffOverlay() {
            document.getElementById('playoff-overlay-wrapper').style.display = 'none';
            document.getElementById('ranking-content').style.display = 'block';
            localStorage.setItem('playoff-overlay-dismissed', '1');
        }
        if (localStorage.getItem('playoff-overlay-dismissed')) {
            document.getElementById('playoff-overlay-wrapper').style.display = 'none';
            document.getElementById('ranking-content').style.display = 'block';
        }
        </script>
        <?php else: ?>
        <?php if (!is_user_logged_in()) {
            global $wp;
            loginFormHTML(home_url( $wp->request ));
        } else { ?>
        <div class="row text-left no-margin nothing">
            <div class="container container-ranking black-text">
                <?php if ($rankingCache) {
                    rankingHTMLCached($competition, $friendsCache, $userBets);
                } else {
                    rankingHTML($competition, $betsTable, $userBets);
                } ?>
            </div>
        </div>
        <?php } ?>
        <?php endif; ?>
    </section>
<?php
get_footer();
