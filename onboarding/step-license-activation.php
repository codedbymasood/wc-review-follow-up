<?php
/**
 * Onboarding license activation page.
 *
 * @package plugin-slug\admin\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

defined( 'ABSPATH' ) || exit;

$product_lists = apply_filters( 'stobokit_product_lists', array() );

$product_id = isset( $product_lists[ $plugin_slug ] ) && isset( $product_lists[ $plugin_slug ]['id'] ) ? $product_lists[ $plugin_slug ]['id'] : 0;
?>

<div class="license-activation">
	<div class="header">
		<h2><?php esc_html_e( 'Activate Your License', 'plugin-slug' ); ?></h2>
		<p><?php esc_html_e( 'Enter your license key to unlock automatic updates and premium support.', 'plugin-slug' ); ?></p>
		<?php wp_nonce_field( 'stobokit_license', 'stobokit_license_nonce' ); ?>
	</div>
	<table>
		<tbody>
			<tr data-id="<?php echo esc_attr( $product_id ); ?>" data-slug="<?php echo esc_attr( $plugin_slug ); ?>">
				<td>
					<input name="<?php echo esc_attr( $plugin_slug ); ?>" type="password" placeholder="<?php esc_html_e( 'Enter license key', 'plugin-slug' ); ?>">
					<span class="activate-license btn"><?php esc_html_e( 'Activate', 'plugin-slug' ); ?></span>
					<span class="license-notice" style="top: auto; bottom: 0; right: 0;"></span>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="footer">
		<p><?php esc_html_e( 'Don\'t know your license key?', 'plugin-slug' ); ?> <a href="https://storeboostkit.com/account/?tab=licenses"><?php esc_html_e( 'Copy it from your account', 'plugin-slug' ); ?></a></p>
	</div>
</div>
