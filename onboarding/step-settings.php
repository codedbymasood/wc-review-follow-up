<?php
/**
 * Onboarding welcome page.
 *
 * @package review-follow-up-for-woocommerce\admin\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

defined( 'ABSPATH' ) || exit;

?>

<div class="settings">
	<h2><?php esc_html_e( 'Configure General Settings', 'review-follow-up-for-woocommerce' ); ?></h2>
	<p><?php esc_html_e( 'Set your default preferences for product expiry. You can always change these later in the plugin settings.', 'review-follow-up-for-woocommerce' ); ?></p>
	<div class="section setting-fields">
		<form>
			<?php wp_nonce_field( 'stobokit_save_settings', 'stobokit_save_settings_nonce' ); ?>
			<div class="field-wrap">
				<label><?php esc_html_e( 'Send email when order total exceeds (x)', 'review-follow-up-for-woocommerce' ); ?></label>
				<input type="number" name="revifoup_exceed_order_amount">
			</div>
			<div class="field-wrap">
				<label><?php esc_html_e( 'Email will be sent in (x) days', 'review-follow-up-for-woocommerce' ); ?></label>
				<input type="number" name="revifoup_sent_email_days">
			</div>
			<span class="save-general-settings btn btn-green"><?php esc_html_e( 'Save', 'review-follow-up-for-woocommerce' ); ?></span>

			<span class="settings-notice below"></span>
		</form>
	</div>
</div>
