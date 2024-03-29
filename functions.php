<?php

define('ENROPORRA_DEBUG',true);

add_filter( 'show_admin_bar', '__return_false' );

function redirect_sub_to_home_wpse_93843( $redirect_to, $request, $user ) {
    if ( isset($user->roles) && is_array( $user->roles ) ) {
        if ( in_array( 'subscriber', $user->roles ) ) {
            return home_url( );
        }
    }
    return $redirect_to;
}
add_filter( 'login_redirect', 'redirect_sub_to_home_wpse_93843', 10, 3 );

function enroporra_enqueue_styles_scripts() {
	$version = "20221202-1";
	wp_enqueue_style('font-dosis', 'https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,600,700,800',array(),"1.0");
	wp_enqueue_style('font-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,300,700,800',array(),"1.0");
	wp_enqueue_style('bootstrap',get_template_directory_uri()."/css/bootstrap.min.css",array(),"1.0");
	wp_enqueue_style('theme-style',get_template_directory_uri()."/css/style.css",array('bootstrap'),"1.0");
	wp_enqueue_style('enroporra-style',get_template_directory_uri()."/css/enroporra.css",array('bootstrap','theme-style'),$version);
	wp_enqueue_script('enroporra-scripts',get_template_directory_uri()."/js/scripts.js",array('jquery'),$version,true);
	if (is_front_page()) wp_enqueue_script('frontpage-js',get_template_directory_uri()."/js/front-page.js",array('jquery'),$version);
}
add_action( 'wp_enqueue_scripts', 'enroporra_enqueue_styles_scripts' );

//date_default_timezone_set('europe/madrid');

include get_template_directory()."/templates/fixtures.php";
include get_template_directory()."/templates/ranking.php";
include get_template_directory()."/templates/login.php";

if (class_exists('EP_Competition')) $GLOBALS['ep_competition'] = EP_Competition::getCurrentCompetition();
else if (!is_admin()) {
	die('El plugin Enroporra debe activarse');
}

function weekDay(int $index) : string {
	$weekdays = array(
		__('Domingo','enroporra'),
		__('Lunes','enroporra'),
		__('Martes','enroporra'),
		__('Miércoles','enroporra'),
		__('Jueves','enroporra'),
		__('Viernes','enroporra'),
		__('Sábado','enroporra')
	);
	return $weekdays[$index];
}

if (is_404() && ENROPORRA_DEBUG) {
	/* Produces a dump on the state of WordPress when a not found error occurs */
	/* useful when debugging permalink issues, rewrite rule trouble, place inside functions.php */

	ini_set( 'error_reporting', -1 );
	ini_set( 'display_errors', 'On' );

	echo '<pre>';
	var_dump($_POST);
	add_action( 'parse_request', 'debug_404_rewrite_dump' );
	function debug_404_rewrite_dump( &$wp ) {
		global $wp_rewrite;

		echo '<h2>rewrite rules</h2>';
		echo var_export( $wp_rewrite->wp_rewrite_rules(), true );

		echo '<h2>permalink structure</h2>';
		echo var_export( $wp_rewrite->permalink_structure, true );

		echo '<h2>page permastruct</h2>';
		echo var_export( $wp_rewrite->get_page_permastruct(), true );

		echo '<h2>matched rule and query</h2>';
		echo var_export( $wp->matched_rule, true );

		echo '<h2>matched query</h2>';
		echo var_export( $wp->matched_query, true );

		echo '<h2>request</h2>';
		echo var_export( $wp->request, true );

		global $wp_the_query;
		echo '<h2>the query</h2>';
		echo var_export( $wp_the_query, true );
	}
	add_action( 'template_redirect', 'debug_404_template_redirect', 99999 );
	function debug_404_template_redirect() {
		global $wp_filter;
		echo '<h2>template redirect filters</h2>';
		echo var_export( $wp_filter[current_filter()], true );
	}
	add_filter ( 'template_include', 'debug_404_template_dump' );
	function debug_404_template_dump( $template ) {
		echo '<h2>template file selected</h2>';
		echo var_export( $template, true );

		echo '</pre>';
		exit();
	}
}

// show wp_mail() errors
add_action( 'wp_mail_failed', 'onMailError', 10, 1 );
function onMailError( $wp_error ) {
	echo "<pre>";
	print_r($wp_error);
	echo "</pre>";
}