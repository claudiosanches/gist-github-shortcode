<?php
/*
 * Plugin Name: Gist GitHub Shortcode
 * Plugin URI: http://claudiosmweb.com/plugins/gist-shortcode-wordpress-plugin/
 * Description: Adds Github Gists in your posts via shortcode.
 * Version: 1.1
 * Author: Claudio Sanches
 * Author URI: http://claudiosmweb.com/
 * License: GPLv2 or later
 */

class Gist_Github_Shortcode {

    /**
     * Class construct.
     */
    public function __construct() {

        // Register the shortcode.
        add_shortcode( 'gist', array( &$this, 'shortcode' ) );

        // Actions.
        add_action( 'init', array( &$this, 'buttons_init' ) );
    }

    /**
     * Gist shortcode.
     *
     * Usage:
     * [gist id="Gist ID" file="Gist File (optional)"]
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
            add_filter( 'mce_external_plugins', array( &$this, 'add_buttons' ) );
            add_filter( 'mce_buttons', array( &$this, 'register_buttons' ) );
        }
    }
} // close Gist_Github_Shortcode class.

$gist_github_shortcode = new Gist_Github_Shortcode;
