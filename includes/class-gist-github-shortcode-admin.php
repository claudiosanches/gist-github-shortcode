<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Gist Github Shortcode Admin class.
 */
class Gist_Github_Shortcode_Admin {

	public function __construct() {
		// Init the buttons.
		add_action( 'init', array( $this, 'buttons_init' ) );

		// Register the modal dialog ajax request.
		add_action( 'wp_ajax_github_gist_shortcode', array( $this, 'dialog' ) );
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

new Gist_Github_Shortcode_Admin();
