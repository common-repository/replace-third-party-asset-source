<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://jenn.support
 * @since      1.0.0
 *
 * @package    Replace_Third_Party_Asset_Source
 * @subpackage Replace_Third_Party_Asset_Source/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Replace_Third_Party_Asset_Source
 * @subpackage Replace_Third_Party_Asset_Source/includes
 * @author     Jenn Lee <me@jenn.support>
 */
class Replace_Third_Party_Asset_Source {

	protected $loader;
	protected $plugin_name;
	protected $version;
	protected $prefix;
	protected $plugin_base_file;

	public function __construct() {
		if ( defined( 'REPLACE_THIRD_PARTY_ASSET_SOURCE_VERSION' ) ) {
			$this->version = REPLACE_THIRD_PARTY_ASSET_SOURCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'replace-third-party-asset-source';
		$this->prefix = 'rtpas_';
		$this->plugin_base_file = REPLACE_THIRD_PARTY_ASSET_SOURCE_BASE_FILE;

		$this->load_dependencies();
		$this->define_admin_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-replace-third-party-asset-source-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-replace-third-party-asset-source-admin.php';
		$this->loader = new Replace_Third_Party_Asset_Source_Loader();

	}

	private function define_admin_hooks() {

		$plugin_admin = new Replace_Third_Party_Asset_Source_Admin( 
			$this->get_plugin_name(), 
			$this->get_version(), 
			$this->get_prefix(),
			$this->asset_replacement_lists()
		);
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_plugin_setting' );
		$this->loader->add_filter( 'plugin_action_links_'.$this->get_plugin_base_file() , $plugin_admin, 'add_setting_link' );

		if( $this->use_asset_replace())  {
			// Replace from standard enqueue way
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'replace_asset_source_in_enqueue', 99999999 );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_admin, 'replace_asset_source_in_enqueue', 99999999 );
			// Try replace from loader src output in case the asset enqueue through WP_Styles class directly
			$this->loader->add_filter( 'style_loader_src', $plugin_admin, 'replace_asset_source_style_in_loader', 999999, 2 );
			$this->loader->add_filter( 'script_loader_src', $plugin_admin, 'replace_asset_source_script_in_loader', 999999, 2 );
		}

	}

	public function run() {
		$this->loader->run();
	}


	public function get_plugin_name() {
		return $this->plugin_name;
	}


	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

	public function get_prefix() {
		return $this->prefix;
	}

	public function get_plugin_base_file() {
		return $this->plugin_base_file;
	}

	private function get_setting_value( $key ) {

		$options = get_option( $this->prefix . 'option' );
		if($key === 'all') return $options;
		return (isset($options[ $this->prefix . $key ]))? $options[ $this->prefix . $key ]: '';
	}

	private function use_asset_replace() {
		$use = $this->get_setting_value( 'use_asset_replace' );
		return isset( $use ) && !empty( $use );
	}

	private function asset_replacement_lists() {
		$list = $this->get_setting_value( 'asset_replacement_list' );
		$replacement_list = [];

		if(is_array($list)) {
			
			foreach($list as $ll) {
				if ( $ll['tgt'] != '' && $ll['rpl'] != '') {
					$replacement_list[$ll['tgt']] = $ll['rpl'];
				}
			}
		}

		return $replacement_list;
	}

}
