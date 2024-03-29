<?php
/*
 * $user_login = 'admin';
$user = get_userdatabylogin($user_login);
$user_id = $user->ID;
wp_set_current_user($user_id, $user_login);
wp_set_auth_cookie($user_id);
do_action('wp_login', $user_login);
 */
if ($_POST["enroporra_login_email"]) {
	$user= get_user_by_email($_POST["enroporra_login_email"]);
	if ($user) {
		if (!in_array('administrator',$user->roles)) {
			wp_set_current_user($user->ID);
			wp_set_auth_cookie($user->ID,(bool)$_POST["enroporra_login_remember"]);
			wp_redirect($_POST["enroporra_redirect_url"]);
		}
		else {
			wp_redirect($_POST["enroporra_redirect_url"]."/?login_error=".urlencode(__('Los administradores no pueden hacer login desde aquí','enroporra')));
		}
		exit();
	}
	wp_redirect($_POST["enroporra_redirect_url"]."/?login_error=".urlencode(__('El correo electrónico que has escrito no existe en Enroporra','enroporra')));
	exit();

	// Waiting for better times to come... login with password

	//$user = wp_signon(array('user_login'=>$_POST["enroporra_login_email"], 'user_password'=>$_POST["enroporra_login_password"], 'remember'=>(bool)$_POST["enroporra_login_remember"]));
	//	if (!is_wp_error($user)) {
	//		wp_redirect($_POST["enroporra_redirect_url"]);
	//		exit();
	//	}
	//	wp_redirect($_POST["enroporra_redirect_url"]."/?login_error=".urlencode(__('El correo electrónico o la contraseña que has escrito son incorrectos','enroporra')));
	//	exit();
}
wp_redirect('/');
exit();
