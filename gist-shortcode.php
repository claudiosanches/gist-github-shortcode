<?php
/*
 * Plugin Name: Gist Shortcode
 * Plugin URI: http://claudiosmweb.com/
 * Description: Adds Github Gists in your posts via shortcode.
 * Version: 1.0
 * Author: Claudio Sanches
 * Author URI: http://claudiosmweb.com/
 * License: GPLv2 or later
 */

/**
 * Gist shortcode.
 *
 * Usage:
 * [gist id="Gist ID" file="Gist File (optional)"]
 */
function gistscode_shortcode( $atts ) {
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
add_shortcode( 'gist', 'gistscode_shortcode');


/**
 * Add custom buttons in TinyMCE.
 */
function gistscode_register_buttons( $buttons ) {
    array_push( $buttons, '|', 'gist' );
    return $buttons;
}

/**
 * Register button scripts.
 */
function gistscode_add_buttons( $plugin_array ) {
    $plugin_array['gist'] = plugins_url( 'tinymce/gist.js' , __FILE__ );
    return $plugin_array;
}

/**
 * Register buttons in init.
 */
function gistscode_buttons_init() {
    if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
        return;
    }

    if ( get_user_option( 'rich_editing') == 'true' ) {
        add_filter( 'mce_external_plugins', 'gistscode_add_buttons' );
        add_filter( 'mce_buttons', 'gistscode_register_buttons' );
    }
}
add_action( 'init', 'gistscode_buttons_init' );
