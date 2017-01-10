<?php
/**
 * Plugin Name: BriteVerify GravityForms
 * Plugin URI: http://astoundify.com/downloads/briteverify-gravityforms/
 * Description: {SHORT DESCRIPTION}
 * Version: 1.0.0
 * Author: Astoundify
 * Author URI: http://astoundify.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: briteverify-gravityforms
 * Domain Path: /languages/
**/
if ( ! defined( 'WPINC' ) ) { die; }


/* Constants
------------------------------------------ */

define( 'BV_GF_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'BV_GF_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'BV_GF_FILE', __FILE__ );
define( 'BV_GF_PLUGIN', plugin_basename( __FILE__ ) );
define( 'BV_GF_VERSION', '1.0.0' );


/* Init
------------------------------------------ */

/* Load plugin in "gform_loaded" hook */
add_action( 'gform_loaded', 'bv_gf_init' );

/**
 * Plugin Init
 * @since 0.1.0
 * @link https://www.gravityhelp.com/documentation/article/add-on-framework/
 */
function bv_gf_init(){

	/* Check if method exists */
	if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
		return;
	}

	/* Load Settings */
	require_once( BV_GF_PATH . 'includes/settings.php' );
}



















