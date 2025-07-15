<?php
/**
 * Email template.
 *
 * @package review-requester-for-woocommerce\template\email\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;
?>

<html>
	<head>
		<title><?php esc_html_e( 'Back in Stock!', 'product-availability-notifier-for-woocommerce' ); ?></title>
		<style>
			.main-title {
				color: red;
				text-align: left;
			}
		</style>
	</head>
	<body>
		<div>
			<h2 class="main-title"><?php esc_html_e( 'Good news!', 'product-availability-notifier-for-woocommerce' ); ?></h2>
		</div>
	</body>
</html>
