<?php

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