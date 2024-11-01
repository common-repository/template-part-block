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
 * Register block and related scripts
 *
 * @return void
 */
function register_block() {
	\wp_register_script(
		'wp-block-template-part',
		WP_BLOCK_TEMPLATE_PART_URL . 'assets/js/block.js',
		array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
		WP_BLOCK_TEMPLATE_PART_VER,
		true
	);

	\wp_localize_script(
		'wp-block-template-part',
		'wpbtp',
		array(
			'templateParts'       => get_template_parts(),
			'defaultTemplatePart' => apply_filters( 'wp_block_template_part_default', '' ),
		)
	);

	\register_block_type_from_metadata(
		WP_BLOCK_TEMPLATE_PART_DIR . '/lib/config',
		array(
			'render_callback'   => __NAMESPACE__ . '\render_block_template_part',
			'skip_inner_blocks' => true,
		)
	);
}
\add_action( 'init', __NAMESPACE__ . '\register_block' );

/**
 * Get template parts
 *
 * @return array
 */
function get_template_parts() {
	$files = (array) wp_get_theme()->get_files( 'php', 2, true );

	foreach ( $files as $file => $full_path ) {
		$file = str_replace( '.php', '', $file );

		// phpcs:ignore WordPress.WP.AlternativeFunctions
		if ( preg_match( '|Template Part:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
			$template_parts[] = array(
				'name' => _cleanup_header_comment( $header[1] ),
				'slug' => $file,
			);

			continue;
		}

		if ( strpos( $file, 'template-parts/' ) === 0 ) {
			$template_parts[] = array(
				'name' => $file,
				'slug' => $file,
			);
		}
	}

	$template_parts = apply_filters( 'wp_block_template_part_parts', $template_parts, $files );

	return $template_parts;
}

/**
 * Renders the post template part on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 * @return string  Returns the template part for the given post.
 */
function render_block_template_part( $attributes, $content, $block ) {
	$is_block_editor = defined( 'REST_REQUEST' ) && true === REST_REQUEST && isset( $_GET['context'] ) && 'edit' === sanitize_key( $_GET['context'] );

	if ( $is_block_editor ) {
		$post_id = absint( $_GET['postId'] ?? null );
	} else {
		$post_id = absint( $block->context['postId'] ?? null );
	}

	if ( ! $post_id ) {
		return '';
	}

	$template_part = $attributes['templatePart'] ?? '';

	if ( ! $template_part ) {
		$template_part = apply_filters( 'wp_block_template_part_default', '' );
	}

	$content = get_template_part( $post_id, $template_part );

	\wp_reset_postdata();

	return \apply_filters( 'wp_block_template_part_content', sprintf( '<div %1$s>%2$s</div>', get_block_wrapper_attributes(), $content ), $block, $attributes );
}
