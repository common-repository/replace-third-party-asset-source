<?php
/**
 * @link              https://jenn.support
 * @since             1.0.0
 * @package           Replace_Third_Party_Asset_Source
 *
 * @wordpress-plugin
 * Plugin Name:       Replace Asset Source
 * Description:       Replace asset source with your own desired source. Main purpose is to help WordPress users to change the slow third party scripts or styles which using by some plugins but slow load speed in their own country. Not design for auto local host asset file, but sure you can use for it too.
 * Version:           1.3.1
 * Author:            Jenn Lee
 * Author URI:        https://jenn.support
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       replace-third-party-asset-source
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'REPLACE_THIRD_PARTY_ASSET_SOURCE_VERSION', '1.3.0' );
define( 'REPLACE_THIRD_PARTY_ASSET_SOURCE_BASE_FILE', plugin_basename(__FILE__) );


function activate_replace_third_party_asset_source() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-replace-third-party-asset-source-activator.php';
	Replace_Third_Party_Asset_Source_Activator::activate();
}


function deactivate_replace_third_party_asset_source() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-replace-third-party-asset-source-deactivator.php';
	Replace_Third_Party_Asset_Source_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_replace_third_party_asset_source' );
register_deactivation_hook( __FILE__, 'deactivate_replace_third_party_asset_source' );


require plugin_dir_path( __FILE__ ) . 'includes/class-replace-third-party-asset-source.php';


function run_replace_third_party_asset_source() {

	$plugin = new Replace_Third_Party_Asset_Source();
	$plugin->run();

}
run_replace_third_party_asset_source();
