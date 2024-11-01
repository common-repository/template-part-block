<?php
/**
 * Template Part - Gutenberg Block
 *
 * @package   CloudCatch\WpBlockTemplatePart
 * @link      https://cloudcatch.com
 * @author    CloudCatch LLC
 * @copyright Copyright © 2021 CloudCatch LLC
 * @license   GPL-3.0
 */

namespace CloudCatch\WpBlockTemplatePart\Functions;

/**
 * Register REST API fields
 *
 * @return void
 */
function rest_api_post_fields() {
	$post_types = \get_post_types( array( 'show_in_rest' => true ) );

	register_rest_field(
		array_values( $post_types ),
		'template_part',
		array(
			'get_callback' => __NAMESPACE__ . '\rest_field_template_part',
			'schema'       => null,
		)
	);

	\register_rest_route(
		'wpbtp/v1',
		'/parts/(?P<id>\d+)',
		array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => __NAMESPACE__ . '\rest_route_template_parts_part',
			'permission_callback' => __NAMESPACE__ . '\rest_route_template_parts_permissions_check',
		)
	);
}
\add_action( 'rest_api_init', __NAMESPACE__ . '\rest_api_post_fields' );

/**
 * `template_part` meta field callback
 *
 * @param array $object Post object.
 * @return string
 */
function rest_field_template_part( $object ) {
	return get_template_part( $object['id'] );
}

/**
 * Get specific template part
 *
 * @param WP_REST_Request $request The request.
 * @return array
 */
function rest_route_template_parts_part( $request ) {
	$template_part = $request->get_param( 'template_part' );
	$object_id     = $request->get_param( 'id' );

	$content         = '';
	$GLOBALS['post'] = \get_post( $object_id ); // phpcs:ignore

	ob_start();

	\get_template_part( str_replace( '.php', '', $template_part ) );

	$has_template_part = ob_get_clean();

	if ( $has_template_part ) {
		$content = $has_template_part;
	}

	return rest_ensure_response( $content );
}

/**
 * Permissions check template parts
 *
 * @return boolean
 */
function rest_route_template_parts_permissions_check() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return new \WP_Error(
			'rest_forbidden',
			esc_html__( 'Sorry, you are not allowed to do that.', 'wp-block-template-part' ),
			array( 'status' => rest_authorization_required_code() )
		);
	}

	return true;
}
