<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Gist Github Shortcode Admin class.
 */
class Gist_Github_Shortcode_Admin {

	/**
	 * Admin actions.
	 */
	public function __construct() {
		add_action( 'admin_head', array( $this, 'add_shortcode_button' ) );
		add_filter( 'mce_external_languages', array( $this, 'add_tinymce_locales' ), 20, 1 );
	}

	/**
	 * Add a button for shortcodes to the WP editor.
	 */
	public function add_shortcode_button() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array( $this, 'add_shortcode_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'register_shortcode_button' ) );
		}
	}

	/**
	 * Add the shortcode button to TinyMCE.
	 *
	 * @param  array $plugins TinyMCE plugins.
	 *
	 * @return array
	 */
	public function add_shortcode_tinymce_plugin( $plugins ) {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$plugins['gist'] = plugins_url( 'assets/js/gist' . $suffix . '.js', plugin_dir_path( __FILE__ ) );

		return $plugins;
	}

	/**
	 * Register the shortcode button.
	 *
	 * @param  array $buttons
	 *
	 * @return array
	 */
	public function register_shortcode_button( $buttons ) {
		array_push( $buttons, '|', 'gist' );

		return $buttons;
	}

	/**
	 * TinyMCE locales function.
	 *
	 * @param  array $locales TinyMCE locales.
	 *
	 * @return array
	 */
	public function add_tinymce_locales( $locales ) {
		$locales['gist'] = plugin_dir_path( __FILE__ ) . 'i18n.php';

		return $locales;
	}
}

new Gist_Github_Shortcode_Admin();
