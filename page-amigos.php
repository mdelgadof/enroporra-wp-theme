<?php
    /**
     * @var EP_Competition $competition
     */
    $competition = (object) $GLOBALS['ep_competition'];
    get_header();
?>
    <section class="main__middle__container first-section">
        <div class="titles aboutus text-left">
            <div class="container">
                <div class="seper"></div>
                <h2 class="page__title"><?php _e('Mis amigos','enroporra') ?></h2>
                <p class="small-paragraph"><?php (time()%2==0) ? _e('"Juego para ser feliz, no para ganar nada" (Andrés Iniesta)','enroporra') : _e('"Ganar, eso es lo más importante para mí. Es tan simple como eso" (Cristiano Ronaldo)','enroporra'); ?></p>
            </div>
        </div>
        <?php if (!is_user_logged_in()) {
            global $wp;
            loginFormHTML(home_url( $wp->request ));
        } else {
            $user = new EP_User(get_current_user_id());
            if (count($_POST)) {
                $friends = array();
                foreach ($_POST as $key => $value) {
                    if (substr($key,0,7)=="friend_" && $value==1) {
                        $friends[]=explode("_",$key)[1];
                    }
                }
                $user->setBetFriendsIds($friends);
            }
            else $friends = $user->getBetFriendsIds();
            ?>
        <div class="row text-left no-margin nothing">
            <div class="container black-text" style="font-size:18px">
                <h3><?php _e("Elige a tus amigos","enroporra") ?></h3>
                <form method="post">
                    <?php
                    $bets = $competition->getBets($paid=false);
                    function cmp(EP_Bet $a, EP_Bet $b) {
                        $name1 = str_replace(array("á","é","í","ó","ú"),array("a","e","i","o","u"),mb_strtolower($a->getName()));
	                    $name2 = str_replace(array("á","é","í","ó","ú"),array("a","e","i","o","u"),mb_strtolower($b->getName()));
                        return ($name1>$name2);
                    }
                    usort($bets,"cmp");
                    $list='';
                    foreach ($bets as $bet) {
                        $checked = (in_array($bet->getId(),$friends)) ? "checked" : "";
                        $list .= "<input style='width:inherit' type='checkbox' name='friend_".$bet->getId()."' value='1' ".$checked." /> ".$bet->getName()."<br />";
                    }
                    echo $list;
                    ?>
                    <input type="submit" style="margin-top:20px" class="btn btn-default btn-lg" value="<?php _e("Enviar","enroporra") ?>" />
                </form>
            </div>
        </div>
        <?php } ?>
    </section>
<?php
get_footer();
