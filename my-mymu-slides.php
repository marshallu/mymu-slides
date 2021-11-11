<?php
/**
 * MU MyMU Slides
 *
 * This plugin allows MyMU to pull in slides from WordPress
 *
 * @package mymu-slides
 *
 * Plugin Name: MU MyMU Slides
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

/**
 * The query to get the slides for the REST endpoint
 *
 * @return array
 */
function mymu_slides_query() {

	if ( false === get_transient( 'mu_mymu_slides' ) ) {
		$args = array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 25,
			'orderby'        => array(
				'meta_value_num' => 'ASC',
				'date'           => 'DESC',
			),
			'meta_key'       => 'order_by', // phpcs:ignore
			'meta_query'     => array( // phpcs:ignore
				'relation' => 'AND',
				array(
					'key'   => 'mymu_slides_add_to_mymu_slideshow', // phpcs:ignore
					'value' => true, // phpcs:ignore
				),
				array(
					'key'     => 'mymu_slides_expire_date',
					'value'   => date( 'Y-m-d h:i:s' ), // phpcs:ignore
					'type'    => 'DATETIME',
					'compare' => '>',
				),
			),
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

		set_transient( 'mu_mymu_slides', $data, 43000 );
	} else {
		$data = get_transient( 'mu_mymu_slides' );
	}

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

/**
 * Delete the transient for slides when post is updated.
 *
 * @param string $new The string of the new post type.
 * @param string $old The string of the old post type.
 * @param object $post The post object.
 * @return void
 */
function mu_mymu_slides_purge_transient_on_publish( $new, $old, $post ) {
	if ( 'publish' === $new ) {
		delete_transient( 'mu_mymu_slides' );
	}
}
add_action( 'transition_post_status', 'mu_mymu_slides_purge_transient_on_publish', 10, 3 );
