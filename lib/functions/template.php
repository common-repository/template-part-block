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
 * Helper function to get template part for a given post
 *
 * @param  int|WP_Post $post Post to get template part for.
 * @param  string      $template_part Default template part.
 * @return string
 */
function get_template_part( $post, $template_part = '' ) {
	$content         = '';
	$post            = \get_post( $post );
	$GLOBALS['post'] = $post; // phpcs:ignore

	$template_part = $template_part ?: 'template-parts/content-' . $post->post_type;

	ob_start();

	\get_template_part( \apply_filters( 'wp_block_template_part', str_replace( '.php', '', $template_part ), $post ) );

	$has_template_part = ob_get_clean();

	\wp_reset_postdata();

	if ( $has_template_part ) {
		$content = $has_template_part;
	}

	return $content;
}
