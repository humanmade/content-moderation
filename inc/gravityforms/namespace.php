<?php
/**
 * Content Moderation.
 */

declare( strict_types=1 );

namespace Content_Moderation\GravityForms;

use Content_Moderation\Utils;
use Content_Moderation\Constants;

/**
 * Bootstrapper
 *
 * @return void
 */
function bootstrap(): void {
	add_filter( 'gform_pre_validation', __NAMESPACE__ . '\\maybe_enforce_network_wide_form_content_moderation' );
}

/**
 * Maybe enforce network wide form content moderation.
 *
 * Normally each site would determine if a particular form's
 * content is to be moderated. Now if network wide content
 * moderation is enforced then content for all forms on all
 * sites will be moderated based on the disallowed keys set
 * in network settings.
 *
 * Actual form content moderation is done by Gravity Forms
 * gp-blocklist plugin.
 *
 * @param array $form The form which is about to be validated.
 * @return array
 */
function maybe_enforce_network_wide_form_content_moderation( array $form ): array {
	if ( ! class_exists( 'GP_Blocklist' ) ) {
		// Bail early if gp-blocklist plugin is not active.
		return $form;
	}

	$content_moderation_settings = Utils\get_content_moderation_settings();

	if ( $content_moderation_settings[ Constants\SETTING_ENFORCE_NETWORK_WIDE_CONTENT_MODERATION ] ) {
		$form['gp-blocklist_enable'] = true;
		$form['validationSummary'] = true;
	}

	return $form;
}
