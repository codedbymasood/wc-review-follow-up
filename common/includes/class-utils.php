<?php
/**
 * Utils class.
 *
 * @package review-follow-up-for-woocommerce\admin\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

defined( 'ABSPATH' ) || exit;

/**
 * Utils class.
 */
class Utils {

	/**
	 * Convert string cases
	 *
	 * @param string $string String.
	 * @param string $to_case Change text case.
	 * @return string
	 */
	public static function convert_case( $string, $to_case = 'kebab' ) {
		// Normalize the string: replace dashes and underscores with spaces.
		$string = preg_replace( '/[_\-]+/', ' ', $string );
		$string = strtolower( $string );

		$words = explode( ' ', $string );

		switch ( $to_case ) {
			case 'snake':
				return implode( '_', $words );
			case 'kebab':
				return implode( '-', $words );
			case 'camel':
				return lcfirst( str_replace( ' ', '', ucwords( implode( ' ', $words ) ) ) );
			case 'pascal':
				return str_replace( ' ', '', ucwords( implode( ' ', $words ) ) );
			case 'title':
				return ucwords( implode( ' ', $words ) );
			default:
				return $string;
		}
	}

	/**
	 * Generate random string.
	 *
	 * @param integer $length Length.
	 * @return string
	 */
	public static function generate_random_string( $length = 8 ) {
		$random_string = '';
		$characters    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		for ( $i = 0; $i < $length; $i++ ) {
			$random_string .= $characters[ random_int( 0, strlen( $characters ) - 1 ) ];
		}
		return $random_string;
	}

	public static function parse_review_email( $html, $order ) {
		$order_html = '';

		foreach ( $order->get_items() as $item_id => $item ) {
			$product = $item->get_product();

			$order_html .= '<h3>' . esc_html( $product->get_name() ) . '</h3>';
			$order_html .= '<a href="' . esc_url( get_permalink( $product->get_ID() ) ) . '">' . esc_html__( 'Leave a Review', 'review-follow-up-for-woocommerce' ) . '</a>';
		}

		/* Translators: %1$s: First name, %2$s: Last name */
		$name = sprintf( '%1$s %2$s', esc_html( $order->get_billing_first_name() ), esc_html( $order->get_billing_last_name() ) );

		$site_name = get_bloginfo( 'name' );

		return str_replace( array( '[customer_name]', '[items]', '[site_name]' ), array( $name, $order_html, $site_name ), $html );
	}
}
