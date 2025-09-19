<?php
/**
 * Hooks class.
 *
 * @package plugin-slug\common\includes\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

defined( 'ABSPATH' ) || exit;

/**
 * Settings class.
 */
class Hooks {

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		$this->register_mail_tags();
	}

	public function register_mail_tags() {

		revifoup()->emailer->register_shortcode(
			'ordered_items',
			function ( $args ) {
				$order_id = isset( $args['order_id'] ) ? $args['order_id'] : 0;

				$order = wc_get_order( $order_id );

				if ( ! $order ) {
					revifoup()->logger->info(
						'Order not found.',
						array(
							'order_id' => $order_id,
						)
					);
					return;
				}

				$items = $order->get_items();

				$html = '';
				foreach ( $items as $item_id => $item ) {
					$product = $item->get_product();

					if ( ! $product ) {
						revifoup()->logger->info(
							'Product not found in this order.',
							array(
								'order_id' => $order_id,
							)
						);
						continue;
					}

					if ( apply_filters( 'revifoup_category_excluded', false, $product ) ) {
						continue;
					}

					$product_name = $item->get_name();
					$product_url  = $product->get_permalink();

					$html .= '<div class="order-item">';
					$html .= '<h4>' . esc_html( $product_name ) . '</h4>';
					$html .= '<a href="' . esc_url( $product_url . '#tab-reviews' ) . '">' . esc_html__( 'Leave a Review', 'plugin-slug' ) . '</a>';
					$html .= '</div>';
				}

				return $html;
			}
		);
	}
}

new Hooks();
