<?php
/**
 * Content Moderation utils.
 */

declare( strict_types=1 );

namespace Content_Moderation\Utils;

use Content_Moderation\Constants;

/**
 * Get content moderation settings.
 *
 * @return array
 */
function get_content_moderation_settings(): array {
	switch_to_blog( get_main_site_id() );
	$content_moderation_settings = get_option( Constants\OPTION, [] );
	restore_current_blog();

	return $content_moderation_settings;
}
