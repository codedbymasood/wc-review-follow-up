<?php
/**
 * Email template.
 *
 * @package review-requester-for-woocommerce\template\email\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

$default_html = "Hi [customer_name],

Thanks again for your recent order! We hope everything arrived in perfect shape and that you're loving your new purchases.

We'd really appreciate it if you could take a moment to review the products you received, your feedback helps us improve and also helps other customers shop with confidence.

Here's what you ordered:

[items]

It only takes a minute, and it means a lot to our small team.
Thanks again for choosing us!

Warmly,
The [store_name] Team";

$email_title    = get_option( 'rrw_review_email_title', '' );
$email_template = get_option( 'rrw_review_email_template', '' );

$html = isset( $email_template['html'] ) ? $email_template['html'] : $default_html;
$css  = isset( $email_template['css'] ) ? $email_template['css'] : '';

// TODO: [customer_name], [items], [store_name] needs to replace the related values.
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
			<?php
				echo wp_kses_post( $html );
			?>
		</div>
	</body>
</html>
