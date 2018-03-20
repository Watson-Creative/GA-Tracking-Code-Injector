<?php
 /*
 Plugin Name: GA Tracking Code Injector (WC)
 Plugin URI: https://github.com/Watson-Creative/GA-Tracking-Code-Injector
 GitHub Plugin URI: https://github.com/Watson-Creative/GA-Tracking-Code-Injector
 description: Inject GA code in appropriate places site-wide - use custom menu to set tracking code ID
 Version: 0.0.1
 Author: Alex Tryon
 Author URI: http://www.alextryonpdx.com
 License: GPL2
 */


function printGAcode(){
	echo '<div id="PANTHEONVARDUMP">';
	$ENV = $_ENV['PANTHEON_ENVIRONMENT'];
	if( $ENV == 'dev') {
		echo '<script async src="https://www.googletagmanager.com/gtag/js?id=UA-116061038-1"></script>
		<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag("js", new Date());gtag("config", "UA-116061038-1");</script>';
	}

	echo '</div>';
}

add_action('wp_head', 'printGAcode');


?>