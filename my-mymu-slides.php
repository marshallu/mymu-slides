<?php
/**
 * MyMU Slides
 *
 * This plugin allows MyMU to pull in slides from WordPress
 *
 * @package mymu-slides
 *
 * Plugin Name: MyMU Slides
 * Plugin URI: https://www.marshall.edu
 * Description: A plugin allowing slides from MyMU to pull data from WordPress.
 * Version: 1.0
 * Author: Christopher McComas
 */

if ( ! class_exists( 'ACF' ) ) {
	return new WP_Error( 'broke', __( 'Advanced Custom Fields is required for this plugin.', 'my_textdomain' ) );
}

require plugin_dir_path( __FILE__ ) . '/acf-fields.php';

/**
 * Flush rewrites whenever the plugin is activated.
 */
function mymu_slides_activate() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mymu_slides_activate' );

/**
 * Flush rewrites whenever the plugin is deactivated, also unregister 'employee' post type and 'department' taxonomy.
 */
function mymu_slides_deactivate() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mymu_slides_deactivate' );

function mymu_slides_query() {

	$args = array(
		'post_type'   => 'attachment',
		'post_status' => 'inherit',
		'meta_key'    => 'mymu_slides_add_to_mymu_slideshow',
		'meta_value'  => true,
	);

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) {
		$data = array();

		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$data[] = array(
				'title'    => get_the_ID(),
				'imageUrl' => wp_get_attachment_url(),
				'url'      => get_field( 'mu_gallery_external_url' ),
			);
		}
	}

	wp_reset_postdata();

	return $data;
}
add_shortcode( 'mymu_slides', 'mymu_slides_query' );

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'mymu-slides/v1',
			'slides',
			array(
				'methods'  => 'GET',
				'callback' => 'mymu_slides_query',
			)
		);
	}
);
