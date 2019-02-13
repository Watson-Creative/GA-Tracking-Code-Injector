<?php
 /*
 Plugin Name: GA Tracking Code Injector (WC)
 Plugin URI: https://github.com/Watson-Creative/GA-Tracking-Code-Injector
 GitHub Plugin URI: https://github.com/Watson-Creative/GA-Tracking-Code-Injector
 description: Add tags for Google Analytics, Google Tag Manager and Facebook Pixel(since 2.1.0) code in appropriate locations globally from WP Admin menu. Code is only printed in a live Pantheon environment to prevent skewing data with traffic on the development or testing environments.
 Version: 2.1.0
 Author: Alex Tryon
 Author URI: http://www.alextryonpdx.com
 License: GPL2
 */


if ( is_admin() ){ // admin actions
  add_action( 'admin_menu', 'ga_inject_create_menu' );
  add_action( 'admin_init', 'register_ga_inject_settings' );
}



function printGAcode_head(){
	if( $_ENV['PANTHEON_ENVIRONMENT'] == 'live') {
		
		// Google Analytics Header Code
		$GA_CODE = get_option("ga_inject_code");
		if($GA_CODE != 'UA-XXXXX-X' && $GA_CODE != ''):
			echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . get_option("ga_inject_code") . '"></script>
				<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag("js", new Date());gtag("config", "' . $GA_CODE . '");</script>';
		endif;

		// Google Tag Manager Header Code
		$GTM_CODE = get_option("gtm_inject_code");
		if($GTM_CODE != 'GTM-XXXX' && $GTM_CODE != ''):
			echo "<!-- Google Tag Manager -->
				<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','" . $GTM_CODE . "');</script>
				<!-- End Google Tag Manager -->";
		endif;

		// Google Tag Manager Header Code
		$FB_PIXEL_CODE = get_option("fb_pixel_code");
		if($FB_PIXEL_CODE != '###############' && $FB_PIXEL_CODE != ''):
			echo '<!-- Facebook Pixel Code -->
				<script>
				!function(f,b,e,v,n,t,s)
				{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};
				if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version=\'2.0\';
				n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[0];
				s.parentNode.insertBefore(t,s)}(window, document,\'script\',
				\'https://connect.facebook.net/en_US/fbevents.js\');
				fbq(\'init\', \'' . $FB_PIXEL_CODE . '\');
				fbq(\'track\', \'PageView\');
				</script>
				<noscript><img height="1" width="1" style="display:none"
				src="https://www.facebook.com/tr?id=' . $FB_PIXEL_CODE . '&ev=PageView&noscript=1"
				/></noscript>
				<!-- End Facebook Pixel Code -->';
		endif;
	}
}
// add_action('wp_headers', 'printGAcode');
add_action('wp_head', 'printGAcode_head');


// filter hack via https://www.affectivia.com/blog/placing-the-google-tag-manager-in-wordpress-after-the-body-tag/
if( $_ENV['PANTHEON_ENVIRONMENT'] === 'live') {
	add_filter( 'body_class', 'gtm_add', 10000 );
}

function gtm_add( $classes ) {

	

	$GTM_CODE = get_option("gtm_inject_code");
	
	if($GTM_CODE != 'GTM-XXXX' && $GTM_CODE != ''):

		$PRINT_CODE = '<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id="' . $GTM_CODE . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->';

		$classes[] = '">' . $PRINT_CODE . '<br style="display:none';      
		return $classes;

	else :
		return $classes;
	endif;
}



register_activation_hook(__FILE__,'create_default_values');
function create_default_values() {
	if ( get_option( 'ga_inject_code' ) == false ) { 
		add_option("ga_inject_code", 'UA-XXXXX-X'); 
	}
	if ( get_option( 'gtm_inject_code' ) == false ) { 
		add_option("gtm_inject_code", 'GTM-XXXX'); 
	}
	if ( get_option( 'fb_pixel_code' ) == false ) { 
		add_option("fb_pixel_code", '###############'); 
	}
}


function register_ga_inject_settings() { // whitelist options
	register_setting( 'ga-inject-option-group', 'ga_inject_code' );
	register_setting( 'ga-inject-option-group', 'gtm_inject_code' );
	register_setting( 'ga-inject-option-group', 'fb_pixel_code' );
}

function ga_inject_create_menu() {

	//create new top-level menu
	add_menu_page('GA Code Injector Settings', 'GA Code Injector Settings', 'administrator', __FILE__, 'ga_inject_settings_page', plugins_url('img/ga.png', __FILE__ ) );

	//call register settings function
	add_action( 'admin_init', 'register_ga_inject_settings' );
}


function ga_inject_settings_page() {
?>

<div class="wrap">
	<img id="watson-branding" src="<?php echo plugins_url('img/WC_Brand_Signature.png', __FILE__ ); ?>" style="max-width:400px;">
	<h1>Watson Creative Tracking Code Injector</h1>
	<form method="post" action="options.php"> 
		<?php 
		settings_fields( 'ga-inject-option-group' );
		do_settings_sections( 'ga-inject-option-group' ); ?>

		<table class="form-table ga-inject-code-options">

	        <tr valign="top">
		        <th scope="row">Google Analytics Tracking Code (UA-XXXXX-X)</th>
		        <td><input type="text" name="ga_inject_code" value="<?php echo esc_attr( get_option('ga_inject_code') ); ?>" /></td>
	        </tr>

	        <tr valign="top">
		        <th scope="row">Google Tag Manager Container ID (GTM-XXXX)</th>
		        <td><input type="text" name="gtm_inject_code" value="<?php echo esc_attr( get_option('gtm_inject_code') ); ?>" /></td>
	        </tr>

	        <tr valign="top">
		        <th scope="row">Facebook Pixel Code (###############)</th>
		        <td><input type="text" name="fb_pixel_code" value="<?php echo esc_attr( get_option('fb_pixel_code') ); ?>" /></td>
	        </tr>
	  
	    </table>

    <?php
		submit_button('Save Changes');
		?>
	</form>
</div>







<?php } ?>