<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jenn.support
 * @since      1.0.0
 *
 * @package    Replace_Third_Party_Asset_Source
 * @subpackage Replace_Third_Party_Asset_Source/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Replace_Third_Party_Asset_Source
 * @subpackage Replace_Third_Party_Asset_Source/admin
 * @author     Jenn Lee <me@jenn.support>
 */
class Replace_Third_Party_Asset_Source_Admin {

	private $plugin_name;
	private $version;
	private $prefix;
	private $replacement_list;


	public function __construct( $plugin_name, $version, $prefix, $replacement_list ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->prefix = $prefix;
		$this->replacement_list = $replacement_list;

	}

	public function plugin_styles() {

		$css_setting_file = plugin_dir_url( __FILE__ ) . 'css/replace-third-party-asset-source-setting.css';
		$css_setting_path = plugin_dir_path(__FILE__) . 'css/replace-third-party-asset-source-setting.css';
		wp_enqueue_style( $this->prefix.'setting_css' , $css_setting_file , array(), filemtime( $css_setting_path), 'all' );

	}

	public function plugin_scripts() {

		$js_setting_file = plugin_dir_url( __FILE__ ) . 'js/replace-third-party-asset-source-setting.js';
		$js_setting_path = plugin_dir_path(__FILE__) . 'js/replace-third-party-asset-source-setting.js';
		wp_enqueue_script( $this->prefix.'setting_js' , $js_setting_file , array(), filemtime( $js_setting_path), 'all' );

	}

	
	public function replace_asset_source_in_enqueue() {

		if( empty( $this->replacement_list ) ) return;

		$asset_types = [ 'style', 'script' ];

		foreach( $asset_types as $asset_type ) {

			$asset_matches = $this->match_source_in_enqueue( $asset_type );
			$asset_matches = apply_filters( 'rtpas_enqueue_matched_' . $asset_type , $asset_matches, $this->replacement_list );
			if( !empty( $asset_matches ) ) {
				$this->replace_source( $asset_matches, $asset_type );
			}

		}

	}

	private function match_source_in_enqueue( $asset_type = 'style' ) {

		global $wp_styles;
		global $wp_scripts;

		$matches = array();
		$assets = ( $asset_type == 'style')? $wp_styles : $wp_scripts;

		foreach( $assets->registered as $handler=>$asset ) {

			$source = $asset->src;
			if ( ! is_string( $source ) ) continue;

			$matched_replacement = $this->get_matched_replacement_from_original_source( $source );

			if( $matched_replacement['found'] === false ) continue;

			$matches[$handler] = $matched_replacement;
		}

		return $matches;
	}

	private function replace_source( $matched_list, $asset_type = 'style') {

		global $wp_styles;
		global $wp_scripts;

		$assets = ( $asset_type == 'style')? $wp_styles : $wp_scripts;

		foreach( $matched_list as $handler=>$replacement ) {

			$assets->registered[$handler]->src = $replacement['replace_src'];

		}

	}

	public function replace_asset_source_style_in_loader( $original_src, $handler) {

		if( empty( $this->replacement_list ) ) return $original_src;

		$matched_style = $this->get_matched_replacement_from_original_source( $original_src );
		$matched_style = apply_filters( 'rtpas_loader_matched_style', $matched_style, $original_src, $handler,  $this->replacement_list );

		return ( is_array( $matched_style ) && isset( $matched_style['replace_src'] ) && '' !== $matched_style['replace_src'] && $matched_style['found'] === true )? $matched_style['replace_src'] : $original_src;

	}

	public function replace_asset_source_script_in_loader( $original_src, $handler) {

		if( empty( $this->replacement_list ) ) return $original_src;

		$matched_script = $this->get_matched_replacement_from_original_source( $original_src, $handler );
		$matched_script = apply_filters( 'rtpas_loader_matched_script', $matched_script, $original_src, $handler,  $this->replacement_list );

		return ( is_array( $matched_script ) && isset( $matched_script['replace_src'] ) && '' !== $matched_script['replace_src'] && $matched_script['found'] === true )? $matched_script['replace_src'] : $original_src;

	}

	private function get_matched_replacement_from_original_source( $original_src, $handler = null ) {

		$replacement_lists = $this->replacement_list;
		$matched_replacement = array(
			'target_src' => '',
			'replace_src' => '',
			'found' => false,
		);
		$site_host = parse_url( get_site_url() )['host'];
		$original_url = parse_url( $original_src );

		foreach( $replacement_lists as $target=>$replace ) {

			$target_url = parse_url( $target );
			if( $target_url['host'] == $site_host ) {
				$source_path = $original_url['path'];
				if( $source_path == $target_url['path']) {
					$matched_replacement['target_src'] = $target;
					$matched_replacement['replace_src'] = $replace;
					$matched_replacement['found'] = true;
					break;
				}

			} else {
				if( stripos( $target, $original_url['path']) !== false ) {
					$matched_replacement['target_src'] = $target;
					$matched_replacement['replace_src'] = $replace;
					$matched_replacement['found'] = true;
					break;
				}
				
			}
			
		}

		$matched_replacement = apply_filters( 'rtpas_matched_replacement', $matched_replacement, $replacement_lists, $original_src, $handler );

		return $matched_replacement;
	}

	public function add_setting_link ( $links ) {
		$mylinks = array(
		   '<a href="' . admin_url( 'options-general.php?page='.$this->plugin_name . '-setting' ) . '">Settings</a>',
		);
		$links = array_merge( $links, $mylinks );
		return $links;
	 }

	public function plugin_setting_page() {

		// Only with manage_options can access this page
		if( !current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permission to access this page.'));
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/replace-third-party-asset-source-setting.php' );
		
	}

	public function add_plugin_menu() {

		$setting_page = add_submenu_page(
			'options-general.php',
			'Replace Asset Source',
			'Replace Asset Source',
			'manage_options',
			$this->plugin_name . '-setting',
			array( $this, "plugin_setting_page" )
		);

		// load javascript & css only in this setting page
		add_action('load-' . $setting_page, array( $this, 'plugin_scripts' ) );
		add_action('load-' . $setting_page, array( $this, 'plugin_styles' ) );
	}


	public function register_plugin_setting() {

		$option_group = $this->prefix . 'group';
		register_setting( $option_group, $this->prefix . 'option', 'handle_sanitization_validation_escaping_text');

		$setting_section = $this->prefix . 'section';
		add_settings_section(
			$setting_section,
			'',
			'',
			$option_group
		);

		$fields = array(
			[
				'name' => 'use_asset_replace',
				'title' => 'Activate Asset Replacement',
				'callback' => 'plugin_setting_form_checkbox_output',
				'args' => [
					'text' => 'Yes',
				]
			],
			[
				'name' => 'asset_replacement_list',
				'title' => 'Replacement List',
				'callback' => 'plugin_replacement_list_output',
				'args' => []
			],
		);

		if ( count($fields) > 0 ) {
			
			foreach( $fields as $key=>$field ) {

				$name = $this->prefix . $field['name'];
				$title = $field['title'];
				$callback = $field['callback'];
				$args = array(
					'name' => $name,
					'class' => $name
				);
				if ( !empty($field['args']) ) {
					if ( !empty($field['args']['class']) ) {
						$args['class'] .= ' '.$field['args']['class'];
					}
					if ( !empty($field['args']['text']) ) {
						$args['text'] = $field['args']['text'];
					}
				};

				add_settings_field(
					$name,
					$title,
					array( $this, $callback),
					$option_group,
					$setting_section,
					$args
				);
			}
		}

	}

	public function handle_sanitization_validation_escaping_text( $option ) {

		$option = sanitize_text_field($option);
		return $option;
	}

	public function plugin_setting_form_checkbox_output( $args ) {

		$input_name = $this->prefix . 'option[' . $args['name'] . ']';
		$options = get_option( $this->prefix . 'option' );
		$checked = ( empty($options[$args['name']]) ) ? '' : 'checked' ;
		
		printf('<label for="'. $input_name .'"><input type="checkbox" name="'.$input_name.'" value="1" '. $checked .' />'. $args['text'] .'</label>');
	}

	public function plugin_replacement_list_output( $args ) {

		$input_name = $this->prefix . 'option[' . $args['name'] . ']';
		$options = get_option( $this->prefix . 'option' );
		$replacement_list = $options[$args['name']] ;
		$jsonReplacementList = json_encode($replacement_list);
		$additional_variables = "const inputName = '$input_name';";
		$additional_variables .= "const jsonReplacementList = $jsonReplacementList;";
		wp_add_inline_script($this->prefix.'setting_js', $additional_variables, 'before');
		include( plugin_dir_path( __FILE__ ) . 'partials/replace-third-party-asset-source-replacement-list.php' );

	}

}
