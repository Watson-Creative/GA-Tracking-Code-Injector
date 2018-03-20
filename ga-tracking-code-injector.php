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
	echo '<h1>' . get_option('ga_inject_code') . '</h1>';
	// echo '<div id="PANTHEONVARDUMP">';
	// $ENV = $_ENV['PANTHEON_ENVIRONMENT'];
	// if( $ENV == 'live') {
	// 	echo '<script async src="https://www.googletagmanager.com/gtag/js?id=UA-116061038-1"></script>
	// 	<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag("js", new Date());gtag("config", "UA-116061038-1");</script>';
	// }

	// echo '</div>';
}

add_action('wp_head', 'printGAcode');


function register_ga_inject_settings() { // whitelist options
	register_setting( 'ga_inject_option-group', 'ga_inject_code' );
}

function freeze_create_menu() {

	//create new top-level menu
	add_menu_page('GA Code Injector Settings', 'GA Inject Settings', 'administrator', __FILE__, 'ga_inject_settings_page', plugins_url('img/WC_Brand-20.png', __FILE__ ) );

	//call register settings function
	add_action( 'admin_init', 'register_ga_inject_settings' );
}


function ga_inject_settings_page() {
?>

<div class="wrap">
	<h1>Watson Creative Google Analytics Code Injector</h1>
	<form method="post" action="options.php"> 
		<?php 
		settings_fields( 'ga_inject_code-group' );
		do_settings_sections( 'ga_inject_code-group' ); ?>

		<table class="form-table ga_inject_code-options">

	        <tr valign="top">
		        <th scope="row">Tracking Code (AZ-1234567-8)</th>
		        <td><input type="text" name="ga_inject_code" value="<?php echo esc_attr( get_option('ga_inject_code') ); ?>" /></td>
	        </tr>
	  
	    </table>

    <?php
		submit_button('Save Changes');
		?>
	</form>
</div>







<?php } ?>
?>