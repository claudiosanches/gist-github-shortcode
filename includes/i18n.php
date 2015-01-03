<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$strings = 'tinyMCE.addI18n({' . _WP_Editors::$mce_locale . ': {
	gist: {
		title: "' . esc_js( __( 'Gist', 'gist-github-shortcode' ) ) . '",
	}
}});';
