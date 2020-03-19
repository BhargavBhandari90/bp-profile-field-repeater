<?php
/**
 * Plugin Name:     BuddyPress Profile Field Repeater
 * Plugin URI:      https://bhargavb.wordpress.com/
 * Description:     Make profile field as a repeater.
 * Author:          Bunty
 * Author URI:      https://bhargavb.wordpress.com/about/
 * Text Domain:     bp-field-repeater
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Bp_Profile_Fields_Repeater
 */

/**
 * Main file, contains the plugin metadata and activation processes
 *
 * @package    Bp_Profile_Fields_Repeater
 */
if ( ! defined( 'BPPFR_VERSION' ) ) {
	/**
	 * The version of the plugin.
	 */
	define( 'BPPFR_VERSION', '1.0.0' );
}

if ( ! defined( 'BPPFR_PATH' ) ) {
	/**
	 *  The server file system path to the plugin directory.
	 */
	define( 'BPPFR_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'BPPFR_URL' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'BPPFR_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'BPPFR_BASE_NAME' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'BPPFR_BASE_NAME', plugin_basename( __FILE__ ) );
}

/**
 * Apply transaltion file as per WP language.
 */
function bprpa_text_domain_loader() {

	// Get mo file as per current locale.
	$mofile = BPPFR_PATH . 'languages/' . get_locale() . '.mo';

	// If file does not exists, then applu default mo.
	if ( ! file_exists( $mofile ) ) {
		$mofile = BPPFR_PATH . 'languages/default.mo';
	}

	load_textdomain( 'bp-field-repeater', $mofile );
}

add_action( 'plugins_loaded', 'bprpa_text_domain_loader' );

/**
 * Display admin notice if BuddyPress is not activated.
 */
function bppfr_admin_notice_error() {

	if ( function_exists( 'bp_is_active' ) ) {
		return;
	}

	// Notice class.
	$class = 'notice notice-error';

	// Get plugin name.
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_name = $plugin_data['Name'];

	$message = sprintf(
		/* translators: %1$s: Plugin's name, %2$s: Plugin's name */
		__( '%1$s works with BuddyPress only. Please activate BuddyPress or de-activate %2$s.', 'bp-repost-activity' ),
		esc_html( $plugin_name ),
		esc_html( $plugin_name )
	);

	printf(
		'<div class="%1$s"><p>%2$s</p></div>',
		esc_attr( $class ),
		esc_html( $message )
	);

}

add_action( 'admin_notices', 'bppfr_admin_notice_error' );

// Include functions file.
require BPPFR_PATH . 'app/main/class-bp-field-repeater.php';
require BPPFR_PATH . 'app/includes/common-functions.php';
require BPPFR_PATH . 'app/admin/class-bp-field-repeater-admin.php';
