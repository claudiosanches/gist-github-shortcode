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

		if ( is_admin() ) {
			include_once 'includes/class-gist-github-shortcode-admin.php';
		}
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
		$args = shortcode_atts( array(
			'id'   => '',
			'file' => '',
		), $atts ) );

		$file = $args['file'] ? '?file=' . esc_attr( $args['file'] ) : '';

		$gist = sprintf(
			'<script src="https://gist.github.com/%s.js%s"></script>',
			esc_attr( $args['id'] ),
			$file
		);

		return $gist;
	}
}

add_action( 'plugins_loaded', array( 'Gist_Github_Shortcode', 'get_instance' ), 0 );

endif;
