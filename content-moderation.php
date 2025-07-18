<?php
/**
 * Plugin Name: Content Moderation
 * Plugin URI:  https://github.com/humanmade/content-moderation
 * Description: Extends content moderation functionality of a WordPress site.
 * Version:     0.1.1
 * Author:      Human Made
 * Author URI:  https://humanmade.com/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: content-moderation
 * Requires at least: 5.8
 * Requires PHP: 8.1
 */

declare( strict_types=1 );

namespace Content_Moderation;

require_once __DIR__ . '/inc/constants.php';
require_once __DIR__ . '/inc/utils.php';
require_once __DIR__ . '/inc/admin.php';
require_once __DIR__ . '/inc/gravityforms/namespace.php';
require_once __DIR__ . '/inc/namespace.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );
