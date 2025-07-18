<?php
/**
 * Email template.
 *
 * @package review-requester-for-woocommerce\template\email\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

namespace RRW;

defined( 'ABSPATH' ) || exit;

$default_html = "<p>Hi [customer_name],</p>

<p>Thanks again for your recent order! We hope everything arrived in perfect shape and that you're loving your new purchases.</p>

<p>We'd really appreciate it if you could take a moment to review the products you received, your feedback helps us improve and also helps other customers shop with confidence.</p>

<strong>Here's what you ordered:</strong>

[items]

<p>It only takes a minute, and it means a lot to our small team.</p>

<p>Thanks again for choosing us!</p>

<p>Warmly,</p>
<p>The [site_name] Team</p>";

$email_title    = get_option( 'rrw_review_email_title', esc_html__( 'Quick favor? We\'d love your feedback!', 'review-requester-for-woocommerce' ) );
$email_template = get_option( 'rrw_review_email_template', array() );

$html = isset( $email_template['html'] ) && ! empty( $email_template['html'] ) ? $email_template['html'] : $default_html;
$css  = isset( $email_template['css'] ) ? $email_template['css'] : '';
?>

<html>
	<head>
		<title><?php esc_html( $email_title ); ?></title>
		<style>
			<?php echo esc_html( $css ); ?>
		</style>
	</head>
	<body>
		<div>
			<?php echo wp_kses_post( Utils::parse_review_email( $html, $order ) ); ?>
		</div>
	</body>
</html>
