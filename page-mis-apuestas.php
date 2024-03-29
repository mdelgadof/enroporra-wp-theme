<?php
    /**
     * @var EP_Competition $competition
     */
    $competition = (object) $GLOBALS['ep_competition'];
    $form_nonce_action = 'Create new bet on Enroporra at stage 1 of the current competition';

    get_header();
?>
    <section class="main__middle__container first-section">
        <div class="titles aboutus text-left">
            <div class="container">
                <div class="seper"></div>
                <h2 class="page__title"><?php _e('mis apuestas','enroporra') ?></h2>
                <p class="small-paragraph"><?php (time()%2==0) ? _e('"Juego para ser feliz, no para ganar nada" (Andrés Iniesta)','enroporra') : _e('"Ganar, eso es lo más importante para mí. Es tan simple como eso" (Cristiano Ronaldo)','enroporra'); ?></p>
            </div>
        </div>
        <?php if (!is_user_logged_in()) {
            global $wp;
            loginFormHTML(home_url( $wp->request ));
        } else {
	        $user = new EP_User(get_current_user_id());
	        $bets = $user->getBets($competition);
            ?>
        <div class="row text-left no-margin nothing">
            <div class="container">
                <h3><?php _e('Estas son las apuestas registradas con tu correo','enroporra') ?> <span class="strong"><?php echo $user->getEmail() ?></span></h3>
                <table class="my-bets">
                    <tr>
                        <th><?php _e('Nº apuesta','enroporra') ?></th>
                        <th><?php _e('Nombre (click para ver la apuesta)','enroporra') ?></th>
                        <th><?php _e('Pagada','enroporra') ?></th>
                        <?php if ($competition->getStage()==$competition::BEFORE_PLAYOFF) { ?><th><?php _e('Apuesta segunda fase','enroporra') ?></th><?php } ?>
                    </tr>
	            <?php
	            foreach ($bets as $bet) { ?>
                    <tr>
                        <td class="center"><?php echo $bet->getBetNumber() ?></td>
                        <td><a href="<?php echo $bet->getUrl() ?>" target="_blank"><?php echo $bet->getName() ?></a></td>
                        <td><span style="font-weight:bold; color:<?php echo $bet->isPaid() ? "green":"red" ?>"><?php echo $bet->isPaid() ? __("Pagada","enroporra"):__("No pagada","enroporra") ?></span></td>
                        <?php if ($competition->getStage()==$competition::BEFORE_PLAYOFF) {
                            if (!$bet->isPlayoffFulfilled()) { ?><td><button onclick="location.href='/apuesta/?id=<?php echo $bet->getId() ?>'" class="btn"><?php _e('Hacer apuesta de la segunda fase','enroporra') ?></button></td><?php }
                            else { ?><td><span style="color:green"><strong><?php _e("OK, segunda fase rellenada","enroporra") ?></strong></span> </td><?php }
                        } ?>
                    </tr>
	            <?php } ?>
                </table>
            </div>
        </div>
        <?php } ?>
    </section>
<?php
get_footer();
