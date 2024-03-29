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
                <h2 class="page__title"><?php _e('tu apuesta','enroporra') ?></h2>
                <p class="small-paragraph"><?php (time()%2==0) ? _e('"Juego para ser feliz, no para ganar nada" (Andrés Iniesta)','enroporra') : _e('"Ganar, eso es lo más importante para mí. Es tan simple como eso" (Cristiano Ronaldo)','enroporra'); ?></p>
            </div>
        </div>
        <?php
            if ($competition->getStage()==EP_Competition::BEFORE_KICK_OFF && count($_POST)) {
                $fail = false; $bet = array();
	            try {
		            $bet = EP_Bet::createBetFromForm($competition);
	            }
                catch ( Exception $e ) {
                    $fail = true;
	            }
	            if (is_array($bet)||$fail) { ?>
                    <div class="row grey-info-block text-left">
                        <div class="container">
                            <h3>Error</h3>
                            <p></p>
                            <p><?php echo array_pop($bet) ?></p>
                            <p><?php _e('Por favor, inténtalo de nuevo','enroporra'); ?></p>
                            <p><button onclick="location.href=history.go(-1)" class="btn btn-default btn-lg"><?php _e('Volver a la apuesta','enroporra') ?></button></p>
                        </div>
                    </div>
        <?php
                }
                else {
                    $payment_data = $competition->getPaymentData();
                    ?>
                    <div class="row grey-info-block text-left">
                        <div class="container">
                            <h3><?php _e('¡Apuesta insertada!','enroporra') ?></h3>
                            <p class="bet_number"><span style="color:#000000"><?php _e('Tu número es el','enroporra') ?> </span><?php echo $bet->getBetNumber() ?></p>
                            <p><?php _e('Este es tu número de apuesta en la Enroporra de este año. Te lo hemos enviado también por email, pero por favor, anótalo y guárdalo bien.','enroporra'); ?></p>
                            <p><?php echo sprintf(__('Recuerda que hasta que no recibamos los <strong>%d €</strong>, la apuesta no será válida.','enroporra'),EP_Competition::$amount) ?></p>
                            <p><?php _e('Estos son los métodos de pago:','enroporra') ?></p>
                            <p><strong><?php _e('Transferencia bancaria','enroporra') ?>:</strong></p>
                            <p>&nbsp;&nbsp;<?php _e('Titular','enroporra') ?>: <?php echo $payment_data['owner'] ?></p>
                            <p>&nbsp;&nbsp;<?php _e('Banco','enroporra') ?>: <?php echo $payment_data['bank'] ?></p>
                            <p>&nbsp;&nbsp;<?php _e('IBAN','enroporra') ?>: <?php echo $payment_data['account'] ?></p>
                            <p>&nbsp;&nbsp;<?php _e('Concepto','enroporra') ?>: <?php echo __('Apuesta','enroporra').' '.$bet->getBetNumber().' '.$bet->getName() ?></p>
                            <!--<p><strong><?php _e('Bizum','enroporra') ?>:</strong></p>
                            <p>&nbsp;&nbsp;<?php _e('Número','enroporra') ?>: <?php echo $payment_data['bizum'] ?>*</p>
                            <p>&nbsp;&nbsp;<?php _e('Concepto','enroporra') ?>: <?php echo __('Apuesta','enroporra').' '.$bet->getBetNumber().' '.$bet->getName() ?></p>
                            <p>&nbsp;&nbsp;* <?php _e('Es posible que lleguemos al límite de bizums mensuales en este número, si ves que no funciona prueba por transferencia.','enroporra') ?></p>-->
                        </div>
                    </div>
                    <div class="row grey-info-block text-left">
                        <div class="container">
                            <style>strong.score-bet { color: #e74c3c; }</style>
                            <h3><?php _e("Esta es tu apuesta","enroporra"); echo " "; if ($competition->beforeStart()) _e("para la primera fase","enroporra"); ?>: </h3>
                            <p></p>
                            <?php echo $bet->getHTMLBet() ?>
                        </div>
                    </div>
        <?php
                }
            }
            // Form of part 1 of the bet: Group stage, top scorer and personal data
        else if ($competition->getStage()==EP_Competition::BEFORE_KICK_OFF) {
                $players = $competition->getBetScorers();
        ?>
        <form id="bet1_form" method="post" action="/apuesta/">
            <?php wp_nonce_field( $form_nonce_action, 'ep_bet1_nonce' ); ?>
            <div class="row grey-info-block text-left">
                <div class="container">
                    <div class="col-md-6">
                        <h3><?php _e('Danos tus datos para la apuesta','enroporra'); ?></h3>
                        <p class="small-paragraph"><?php _e('Prometemos usarlos <span class="strong">sólo para contactarte</span> en el caso de que seas uno de los ganadores.','enroporra'); ?></p>
                        <p><input type="email" placeholder="<?php _e('Tu email','enroporra') ?>*" name="enroporra_email" required /> <input type="text" placeholder="<?php _e('Tu teléfono (no obligatorio)','enroporra') ?>" name="enroporra_phone" /></p>
                        <p><input type="email" placeholder="<?php _e('Repite tu email','enroporra') ?>" name="enroporra_email2" required /> <input type="password" placeholder="<?php _e('Contraseña (no obligatoria)','enroporra') ?>" autocomplete="current-password" name="enroporra_password" /></p>
                        <p><input class="long" type="text" placeholder="<?php _e('Nombre y apellidos del/la apostante (sin seudónimos, por favor)*', 'enroporra') ?>" name="enroporra_name" required /></p>
                        <p class="small-paragraph">* <?php _e('Este nombre es el que aparecerá en la clasificación. Aunque el email se puede repetir para gestionar hasta cuatro apuestas, nos gustaría que cada apuesta fuera de una persona distinta.','enroporra'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h3><?php _e('Elige a tu pichichi','enroporra'); ?></h3>
                        <input id="player_id" type="hidden" name="enroporra_player_id" value="" />
                        <p class="small-paragraph"><?php _e('Cada gol que marque te aportará puntos durante todo el campeonato, y si además es máximo goleador del torneo obtendrás más puntos extra.','enroporra'); ?></p>
                        <div class="dropdown">
                            <div class="dropdown-launcher"><?php echo EP_Player::getUnknownPhotoHTML(40).'&nbsp;&nbsp;&nbsp;'.__('Click aquí para elegir al jugador','enroporra') ?></div>
                            <ul id="betScorers" class="dropdown-content">
                                <?php foreach ($players as $player) { ?>
                                    <li data-player_id="<?php echo $player->getId() ?>"><?php echo $player->getPhotoHTML(40)." ".$player->getTeam()->getFlagHTML(20)." ".$player->getName()." (".$player->getTeam()->getName().")" ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row text-left no-margin nothing">
                    <div class="container">
                    </div>
                </div>
                <div class="container">
                    <div class="seper"></div>
                    <h3><?php _e('Anota tus resultados de la fase de grupos','enroporra'); ?></h3>
                    <p><?php _e('Cada vez que aciertes el ganador del partido o el empate obtendrás puntos. Si además aciertas el resultado exacto ganarás más puntos adicionales.','enroporra'); ?></p>
                    <?php $fixtures = $competition->getFixtures(array('tournament'=>'groups')); ?>
                    <?php foreach ($fixtures as $fixture) {
                        $team1 = $fixture->getTeam(1);
                        $team2 = $fixture->getTeam(2);
                    ?>
                    <div class="betFixture">
                        <div class="betFixtureDate"><?php echo $fixture->getDate().' - '.__('Grupo','enroporra').' '.$fixture->getGroup(); ?></div>
                        <div class="betFixtureResult">
                            <div class="betTeamContainer"><?php echo $team1->getFlagHTML(60); ?> <span class="betTeamName"><?php echo $team1->getName() ?></span> <input type="number" name="enroporra_result_<?php echo $fixture->getFixtureNumber() ?>_1" class="betTeamResult" max="15" min="0" required /></div>
                            <div class="betTeamContainer"><?php echo $team2->getFlagHTML(60); ?> <span class="betTeamName"><?php echo $team2->getName() ?></span> <input type="number" name="enroporra_result_<?php echo $fixture->getFixtureNumber() ?>_2" class="betTeamResult" max="15" min="0" required /></div>
                        </div>
                    </div>
                    <?php } ?>
                    <div><p style="text-align: center"><input type="submit" id="submit_bet1_form" class="btn btn-default btn-lg" value="<?php _e('Envía tu apuesta','enroporra'); ?>" role="button" /></p></div>
                </div>
            </div>
        </form>
        <?php }
        else if ( ($competition->getStage()==EP_Competition::BEFORE_PLAYOFF || $_GET["admin"]=="second_stage") && count($_POST) && is_user_logged_in()) {

	        try {
		        $bet = new EP_Bet( $_POST["bet_id"] );
		        $user = new EP_User(get_current_user_id());
		        if ($bet->getOwner()->getId()!=$user->getId() && !$user->isAdmin())
			        throw new Exception();
	        } catch ( Exception $e ) {
		        $error_msg = __('Se ha producido un problema al rellenar tu apuesta. Por favor, vuelve a intentarlo o contacta con la comisión.','enroporra');
	        }
	        if ($error_msg) { ?>
                <div class="container">
                    <h3><?php _e("Se ha producido un error","enroporra") ?></h3>
                    <p><?php echo $error_msg ?></p>
                </div>
		        <?php
	        }
	        else {

		        $bet->setReferee( intval( $_POST["enroporra_referee_id"] ) );

                $scores2 = array();
		        foreach ( $_POST as $key => $value ) {
			        $temp = explode( "_", $key );
			        if ( intval( $temp[0] ) != 0 ) {
				        $fixtureNumber = $temp[0];
                        if ($temp[1]=="team1result") $scores2[$fixtureNumber]['s1'] = $value;
				        if ($temp[1]=="team2result") $scores2[$fixtureNumber]['s2'] = $value;
				        if ($temp[1]=="team1id") $scores2[$fixtureNumber]['t1'] = new EP_Team($value);
				        if ($temp[1]=="team2id") $scores2[$fixtureNumber]['t2'] = new EP_Team($value);
				        if ($temp[1]=="winner") {
                            if ($_POST[$fixtureNumber."_team1result"]>$_POST[$fixtureNumber."_team2result"])
                                $scores2[$fixtureNumber]['winner'] = "1";
					        else if ($_POST[$fixtureNumber."_team1result"]<$_POST[$fixtureNumber."_team2result"])
						        $scores2[$fixtureNumber]['winner'] = "2";
                            else $scores2[$fixtureNumber]['winner'] = $value;
				        }
                        if (!isset($scores2[$fixtureNumber]['fixture']))
                            $scores2[$fixtureNumber]['fixture'] = $competition->getFixtureById($fixtureNumber);

				        $scores2[$fixtureNumber]['player_goals'] = 0;
                        $scores2[$fixtureNumber]['points_score'] = 0;
                        $scores2[$fixtureNumber]['points_winner'] = 0;
			        }
		        }

                $scores1 = $bet->getScores();
                $bet->setScores($scores1+$scores2);

                ?>
                    <div class="row grey-info-block text-left">
                        <div class="container" style="margin-top:40px">
                            <h3><?php _e('¡Apuesta insertada!','enroporra') ?></h3>
                            <p style="margin-top:20px"><?php _e('Ya has terminado. No te olvides de nosotros. Aunque te veas muy abajo en la tabla la segunda fase de la Enroporra siempre genera buenas sorpresas. Te deseamos mucha suerte','enroporra'); ?></p>
                        </div>
                    </div>
                    <div class="row grey-info-block text-left">
                        <div class="container">
                            <style>strong.score-bet { color: #e74c3c; }</style>
                            <h3><?php echo sprintf(__("Esta es la apuesta de %s para la segunda fase","enroporra"),"<span class='strong'>".$bet->getName()."</span>"); ?>: </h3>
                            <p></p>
                            <?php echo $bet->getHTMLBet2() ?>
                        </div>
                    </div>
                <?php
	        }
        } // END submission of second stage
        else if ( ($competition->getStage()==EP_Competition::BEFORE_PLAYOFF || $_GET["admin"]=="second_stage") && is_user_logged_in() ){
	        try {
		        $bet = new EP_Bet( $_GET["id"] );
		        $user = new EP_User(get_current_user_id());
		        if ($bet->getOwner()->getId()!=$user->getId() && !$user->isAdmin())
                    throw new Exception();
	        } catch ( Exception $e ) {
                $error_msg = __('La URL de tu apuesta no es correcta. Por favor, vuelve a Mis Apuestas','enroporra');
	        }
            if ($error_msg) { ?>
                <div class="container">
                    <h3><?php _e("Se ha producido un error","enroporra") ?></h3>
                    <p><?php echo $error_msg ?></p>
                </div>
            <?php
            }
            else {
                $referees = EP_Referee::getAllReferees();
                $labelForm  = ($_GET["admin"]=="second_stage") ? "?admin=second_stage" : "";
            ?>
                <form id="bet2_form" method="post" action="/apuesta/<?php echo $labelForm ?>">
                    <input type="hidden" name="bet_id" value="<?php echo $bet->getId() ?>" />
                    <div class="container" style="margin-bottom: 30px">
                        <h2><?php _e('Rellena la segunda parte de tu Enroporra','enroporra'); ?></h2>
                        <h3 class="strong"><?php echo mb_strtoupper($bet->getName()) ?></h3>
                        <p></p>
                        <h3><?php _e('Elige al árbitro de la final','enroporra'); ?></h3>
                        <div class="col-md-6">
                            <input id="referee_id" type="hidden" name="enroporra_referee_id" value="" />
                            <p class="small-paragraph"><?php _e('Acertar ahora el árbitro que pitará el gran partido dentro de unos días te dará puntos extra.','enroporra'); ?></p>
                            <div class="dropdown">
                                <div class="dropdown-launcher"><?php _e('Click aquí para elegir al árbitro','enroporra') ?></div>
                                <ul id="betReferee" class="dropdown-content">
                                    <?php foreach ($referees as $referee) { ?>
                                        <li data-referee_id="<?php echo $referee->getId() ?>"><?php echo $referee->getTeam()->getFlagHTML(20)." ".$referee->getName()." (".$referee->getTeam()->getName().")" ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="container" style="margin-bottom: 30px">
                        <h3><?php _e('Elige los resultados de los cruces','enroporra'); ?></h3>
                    </div>
                    <div id="bet2-wrapper" class="container">
                        <div class="bet2-container">
                    <?php
                    $fixtures = $competition->getFixtures(array('tournament'=>'last16'));
                    foreach ($fixtures as $fixture) {
                        fixturePlayOffHTML($fixture);
                    }
                    $fixtures = $competition->getFixtures(array('tournament'=>'last8'));
                    foreach ($fixtures as $fixture) {
                        fixturePlayOffHTML($fixture);
                    }
                    ?>
                        </div>
                        <div class="bet2-container semis">
                    <?php
                    $fixtures = $competition->getFixtures(array('tournament'=>'last4'));
                    foreach ($fixtures as $fixture) {
                        fixturePlayOffHTML($fixture);
                    }
                    ?>
                        </div>
                        <div class="bet2-container final">
                    <?php
                    $fixtures = $competition->getFixtures(array('tournament'=>'final'));
                    foreach ($fixtures as $fixture) {
                        fixturePlayOffHTML($fixture);
                    }
                    ?>
                        </div>
                        <div class="bet2-container final" style="margin-bottom: 50px;">
                            <input type="button" id="submit_bet2_form" class="btn btn-default btn-lg" value="<?php _e('Envía tu apuesta','enroporra'); ?>" role="button" />
                        </div>
                    </div>
                </form>
	            <?php
            } /* END else no error */

        } /* END bet 2 */

        else { ?>
            <div class="container">
                <h3><?php _e("Apuestas cerradas para la Enroporra","enroporra")." ".$competition->getName() ?></h3>
                <p><?php _e("Mira que tuviste tiempo, ¿eh? Pues ahora ya se acabó el plazo de insertar apuestas.","enroporra") ?></p>
            </div>
            <?php
        } ?>
    </section>
<?php
get_footer();
