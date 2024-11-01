<?php
/**
 * Plugin Name:     Template Part Block
 * Description:     Gutenberg block to render a template part inside a query loop
 * Version:         1.1.1
 * Author:          CloudCatch
 * Author URI:      https://cloudcatch.io
 * Text Domain:     wp-block-template-part
 * Domain Path:     /languages/
 * Contributors:    cloudcatch, cloudcatch, dkjensen
 * Requires PHP:    7.0.0
 *
 * @package CloudCatch\WpBlockTemplatePart
 */

namespace CloudCatch\WpBlockTemplatePart;

define( 'WP_BLOCK_TEMPLATE_PART_DIR', \plugin_dir_path( __FILE__ ) );
define( 'WP_BLOCK_TEMPLATE_PART_URL', \plugin_dir_url( __FILE__ ) );
define( 'WP_BLOCK_TEMPLATE_PART_VER', '1.1.1' );

require_once WP_BLOCK_TEMPLATE_PART_DIR . '/lib/functions/block.php';
require_once WP_BLOCK_TEMPLATE_PART_DIR . '/lib/functions/rest-api.php';
require_once WP_BLOCK_TEMPLATE_PART_DIR . '/lib/functions/template.php';

/**
 * Setup plugin
 *
 * @return void
 */
function initialize() {
	\load_plugin_textdomain( 'wp-block-template-part', false, WP_BLOCK_TEMPLATE_PART_DIR . '/languages' );
}
\add_action( 'plugins_loaded', __NAMESPACE__ . '\initialize' );
