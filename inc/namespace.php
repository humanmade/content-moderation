<?php
/**
 * Content Moderation.
 */

declare( strict_types=1 );

namespace Content_Moderation;

use Content_Moderation\Utils;

/**
 * Bootstrapper.
 *
 * @return void
 */
function bootstrap(): void {
	Admin\bootstrap();
	GravityForms\bootstrap();

	add_filter( 'pre_option', __NAMESPACE__ . '\\maybe_use_network_disallowed_keys', 10, 2 );
}

/**
 * Maybe use network disallowed keys.
 *
 * If network wide content moderation is enforced to override
 * per site disallowed keys.
 *
 * @param mixed $pre The value to return instead of the option value.
 * @param string $option The option name.
 * @return mixed
 */
function maybe_use_network_disallowed_keys( mixed $pre, string $option ): mixed {
	if ( $option !== 'disallowed_keys' ) {
		// Bail early.
		return $pre;
	}

	global $pagenow;

	if ( is_admin() && $pagenow === 'options-discussion.php' ) {
		/**
		 * Allow each site to still show its own disallowed keys setting value in admin area.
		 *
		 * Note the setting will be disabled if network wide content moderation is enabled and
		 * a warning shown to the dashboard user.
		 */
		return $pre;
	}

	$content_moderation_settings = Utils\get_content_moderation_settings();

	if ( empty( $content_moderation_settings ) ) {
		return $pre;
	}

	if ( $content_moderation_settings[ Constants\SETTING_ENFORCE_NETWORK_WIDE_CONTENT_MODERATION ] ) {
		return $content_moderation_settings[ Constants\SETTING_DISALLOWED_KEYS ];
	}

	return $pre;
}
