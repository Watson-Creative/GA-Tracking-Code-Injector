<?php
 /*
 Plugin Name: GA Tracking Code Injector (WC)
 Plugin URI: https://github.com/Watson-Creative/GA-Tracking-Code-Injector
 GitHub Plugin URI: https://github.com/Watson-Creative/GA-Tracking-Code-Injector
 description: Inject GA code in appropriate places site-wide - use custom menu to set tracking code ID
 Version: 1.0.1
 Author: Alex Tryon
 Author URI: http://www.alextryonpdx.com
 License: GPL2
 */


if ( is_admin() ){ // admin actions
  add_action( 'admin_menu', 'ga_inject_create_menu' );
  add_action( 'admin_init', 'register_ga_inject_settings' );
}



function printGAcode(){
	if( $_ENV['PANTHEON_ENVIRONMENT'] == 'live') {
		echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . get_option("ga_inject_code") . '"></script>
		<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag("js", new Date());gtag("config", "' . get_option("ga_inject_code") . '");</script>';
	}
}
// add_action('wp_headers', 'printGAcode');
add_action('wp_head', 'printGAcode');


register_activation_hook(__FILE__,'create_default_values');
function create_default_values() {
	if ( get_option( 'ga_inject_code' ) == false ) { 
		add_option("ga_inject_code", 'UA-XXXXX-X'); 
	}
}


function register_ga_inject_settings() { // whitelist options
	register_setting( 'ga-inject-option-group', 'ga_inject_code' );
}

function ga_inject_create_menu() {

	//create new top-level menu
	add_menu_page('GA Code Injector Settings', 'GA Code Injector Settings', 'administrator', __FILE__, 'ga_inject_settings_page', plugins_url('img/WC_Brand-20.png', __FILE__ ) );

	//call register settings function
	add_action( 'admin_init', 'register_ga_inject_settings' );
}


function ga_inject_settings_page() {
?>

<div class="wrap">
	<img id="watson-branding" src="<?php echo plugins_url('img/WC_Brand_Signature.png', __FILE__ ); ?>" style="max-width:400px;">
	<h1>Watson Creative Google Analytics Code Injector</h1>
	<form method="post" action="options.php"> 
		<?php 
		settings_fields( 'ga-inject-option-group' );
		do_settings_sections( 'ga-inject-option-group' ); ?>

		<table class="form-table ga-inject-code-options">

	        <tr valign="top">
		        <th scope="row">Tracking Code (UA-XXXXX-X)</th>
		        <td><input type="text" name="ga_inject_code" value="<?php echo esc_attr( get_option('ga_inject_code') ); ?>" /></td>
	        </tr>
	  
	    </table>

    <?php
		submit_button('Save Changes');
		?>
	</form>
</div>







<?php } ?>