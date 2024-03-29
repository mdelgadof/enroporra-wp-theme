<?php
function loginFormHTML($redirectUrl) {
		$form_nonce_action = 'Login Enroporra at distinct pages';
?>
        <form id="login_form" method="post" action="/login/">
		    <?php wp_nonce_field( $form_nonce_action, 'ep_login_nonce' ); ?>
            <div class="row grey-info-block text-left">
                <div class="container">
                    <div class="col-md-6">
                        <h3 style="margin-bottom: 20px;"><?php _e('Esta página requiere tu usuario','enroporra'); ?></h3>
                        <?php if ($_GET["login_error"]) { ?><p class="points-text-inverse"><?php echo $_GET["login_error"] ?></p><?php } ?>
                        <p><input style="width: 100%" type="email" placeholder="<?php _e('Tu email','enroporra') ?>" name="enroporra_login_email" required /> </p>
                        <!--<p><input style="width: 100%" type="password" placeholder="<?php _e('Contraseña (no obligatoria)','enroporra') ?>" autocomplete="current-password" name="enroporra_login_password" /></p>-->
                        <p><input style="width: inherit" type="checkbox" name="enroporra_login_remember" value="1" /> <?php _e('Recordarme en este dispositivo','enroporra') ?></p>
                        <input type="hidden" name="enroporra_redirect_url" value="<?php echo $redirectUrl ?>" />
                        <p style="text-align: center"><input type="submit" id="submit_login_form" class="btn btn-default btn-lg" value="<?php _e('Entrar','enroporra'); ?>" role="button" /></p>
                    </div>
                </div>
            </div>
        </form>
<?php }
