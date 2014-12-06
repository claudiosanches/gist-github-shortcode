<?php
/*
 * Plugin Name: Gist GitHub Shortcode
 * Plugin URI: https://github.com/claudiosmweb/gist-github-shortcode
 * Description: Adds Github Gists in your posts via shortcode.
 * Version: 1.3.0
 * Author: Claudio Sanches
 * Author URI: http://claudiosmweb.com/
 * License: GPLv2 or later
 * Text Domain: gist-github-shortcode
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gist_Github_Shortcode' ) ) :

class Gist_Github_Shortcode {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Class construct.
	 */
	private function __construct() {
		// Load the text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ), 0 );

		// Register the shortcode.
		add_shortcode( 'gist', array( $this, 'shortcode' ) );

		// Init the buttons.
		add_action( 'init', array( $this, 'buttons_init' ) );

		// Register the modal dialog ajax request.
		add_action( 'wp_ajax_github_gist_shortcode', array( $this, 'dialog' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'gist-github-shortcode' );

		load_textdomain( 'gist-github-shortcode', trailingslashit( WP_LANG_DIR ) . 'gist-github-shortcode/gist-github-shortcode-' . $locale . '.mo' );
		load_plugin_textdomain( 'gist-github-shortcode', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Gist shortcode.
	 *
	 * Usage:
	 * [gist id="Gist ID" file="Gist File (optional)"]
	 *
	 * @param  array  $atts Shortcodes attributes (id and file).
	 *
	 * @return string       Gist code.
	 */
	function shortcode( $atts ) {
		extract( shortcode_atts( array(
			'id' => '',
			'file' => '',
		), $atts ) );

		$gist = sprintf(
			'<script src="https://gist.github.com/%s.js%s"></script>',
			esc_attr( $id ),
			$file ? '?file=' . esc_attr( $file ) : ''
		);

		return $gist;
	}

	/**
	 * Add custom buttons in TinyMCE.
	 */
	function register_buttons( $buttons ) {
		array_push( $buttons, '|', 'gist' );
		return $buttons;
	}

	/**
	 * Register button scripts.
	 */
	function add_buttons( $plugin_array ) {
		$plugin_array['gist'] = plugins_url( 'tinymce/gist.js' , __FILE__ );
		return $plugin_array;
	}

	/**
	 * Register buttons in init.
	 */
	function buttons_init() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( true == get_user_option( 'rich_editing') ) {
			add_filter( 'mce_external_plugins', array( $this, 'add_buttons' ) );
			add_filter( 'mce_buttons', array( $this, 'register_buttons' ) );
		}
	}

	/**
	 * Displays the shortcode modal dialog.
	 *
	 * @return string  Modal Dialog HTML.
	 */
	function dialog() {
		@ob_clean();

		include plugin_dir_path( __FILE__ ) . 'tinymce/dialog.php';

		die();
	}

}

add_action( 'plugins_loaded', array( 'Gist_Github_Shortcode', 'get_instance' ), 0 );

endif;
