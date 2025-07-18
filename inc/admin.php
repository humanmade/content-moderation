<?php
/**
 * Content Moderation Admin.
 */

declare( strict_types=1 );

namespace Content_Moderation\Admin;

use Content_Moderation\Constants;
use Content_Moderation\Utils;

/**
 * Bootstrapper
 */
function bootstrap(): void {
	add_action( 'admin_init', __NAMESPACE__ . '\\add_setting' );
	add_action( 'admin_init', __NAMESPACE__ . '\\maybe_disable_site_disallowed_keys_setting' );
	add_action( 'network_admin_menu', __NAMESPACE__ . '\\add_settings_page' );
}

/**
 * Register the setting.
 *
 * @return void
 */
function add_setting(): void {
	register_setting(
		Constants\SETTING,
		Constants\OPTION,
		[
			'sanitize_callback' => __NAMESPACE__ . '\\sanitize_settings',
		]
	);
}

/**
 * Add the settings page at network level.
 *
 * @return void
 */
function add_settings_page(): void {
	add_submenu_page(
		'settings.php',
		__( 'Content Moderation', 'hm-content-moderation' ),
		__( 'Content Moderation', 'hm-content-moderation' ),
		'manage_network',
		Constants\SETTING,
		__NAMESPACE__ . '\\render_settings_page',
		1
	);

	add_settings_section(
		Constants\SECTION,
		'',
		__NAMESPACE__ . '\\render_settings_section',
		Constants\SETTING
	);

	// Add checkbox to enforce network wide content moderation overriding per site settings.
	add_settings_field(
		Constants\SETTING_ENFORCE_NETWORK_WIDE_CONTENT_MODERATION,
		__( 'Enforce Network Wide Content Moderation', 'hm-content-moderation' ),
		__NAMESPACE__ . '\\render_enforce_network_wide_content_moderation_field',
		Constants\SETTING,
		Constants\SECTION
	);

	// Add text area setting for adding disallowed keys to be used when network wide content moderation is enforced.
	add_settings_field(
		Constants\SETTING_DISALLOWED_KEYS,
		__( 'Disallowed Keys', 'hm-content-moderation' ),
		__NAMESPACE__ . '\\render_disallowed_keys_field',
		Constants\SETTING,
		Constants\SECTION
	);
}

/**
 * Render settings page.
 *
 * @return void
 */
function render_settings_page(): void {
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
			<?php settings_fields( Constants\SETTING ); ?>
			<?php do_settings_sections( Constants\SETTING ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Render settings section.
 *
 * @return void
 */
function render_settings_section(): void {
	?>
	<p><?php echo esc_html__( 'When enabled, this will enforce network wide content moderation overriding per site settings.', 'hm-content-moderation' ); ?></p>
	<?php
}

/**
 * Render disallowed keys field.
 *
 * @return void
 */
function render_disallowed_keys_field(): void {
	$content_moderation_settings = Utils\get_content_moderation_settings();
	?>
	<textarea name="<?php echo esc_attr( Constants\OPTION ); ?>[<?php echo esc_attr( Constants\SETTING_DISALLOWED_KEYS ); ?>]" id="<?php echo esc_attr( Constants\SETTING_DISALLOWED_KEYS ); ?>" rows="10" cols="50"><?php echo esc_textarea( $content_moderation_settings[ Constants\SETTING_DISALLOWED_KEYS ] ?? '' ); ?></textarea>
	<?php
}

/**
 * Render enforce network wide content moderation field.
 *
 * @return void
 */
function render_enforce_network_wide_content_moderation_field(): void {
	$content_moderation_settings = Utils\get_content_moderation_settings();
	?>
	<input type="checkbox" name="<?php echo esc_attr( Constants\OPTION ); ?>[<?php echo esc_attr( Constants\SETTING_ENFORCE_NETWORK_WIDE_CONTENT_MODERATION ); ?>]" id="<?php echo esc_attr( Constants\SETTING_ENFORCE_NETWORK_WIDE_CONTENT_MODERATION ); ?>" value="1" <?php checked( $content_moderation_settings[ Constants\SETTING_ENFORCE_NETWORK_WIDE_CONTENT_MODERATION ] ?? false ); ?>>
	<?php
}

/**
 * Sanitize settings.
 *
 * @param array $settings The settings to sanitize before saving.
 * @return array
 */
function sanitize_settings( $settings ): array {
	if ( isset( $settings[ Constants\SETTING_ENFORCE_NETWORK_WIDE_CONTENT_MODERATION ] ) ) {
		$settings[ Constants\SETTING_ENFORCE_NETWORK_WIDE_CONTENT_MODERATION ] = sanitize_text_field( $settings[ Constants\SETTING_ENFORCE_NETWORK_WIDE_CONTENT_MODERATION ] );
	}

	if ( isset( $settings[ Constants\SETTING_DISALLOWED_KEYS ] ) ) {
		$settings[ Constants\SETTING_DISALLOWED_KEYS ] = sanitize_textarea_field( $settings[ Constants\SETTING_DISALLOWED_KEYS ] );
	}

	return $settings;
}

/**
 * Maybe disable a site's disallowed keys setting.
 *
 * @return void
 */
function maybe_disable_site_disallowed_keys_setting(): void {
	$content_moderation_settings = Utils\get_content_moderation_settings();

	if ( ! $content_moderation_settings[ Constants\SETTING_ENFORCE_NETWORK_WIDE_CONTENT_MODERATION ] ) {
		return;
	}

	add_settings_field(
		'hm-content-moderation-disable-site-settings',
		'',
		__NAMESPACE__ . '\\render_disable_site_settings_field',
		'discussion'
	);
}

/**
 * Render disable site settings field.
 *
 * No actual way to filter the disallowed keys setting.
 * So have to resort to using CSS that's conditionally
 * added when this conditional setting field is added
 * to make the disallowed keys setting textarea unclickable.
 *
 * Helpful text is shown to the dashboard user to explain
 * that the setting is disabled.
 *
 * @return void
 */
function render_disable_site_settings_field(): void {
	?>
	<style>
		#wpcontent textarea#disallowed_keys {
			pointer-events: none;
			border: 1px solid red;
			padding: 20px;
			opacity: 0.3;
		}
	</style>
	<p style="color: red; font-style: italic;"><?php echo esc_html__( "This site's disallowed keys setting is disabled as network wide content moderation is enabled.", 'hm-content-moderation' ); ?></p>
	<?php
}
