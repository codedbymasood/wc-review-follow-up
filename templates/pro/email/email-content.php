<?php
/**
 * Email content template.
 *
 * @package plugin-slug\template\email\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

$heading     = isset( $args['heading'] ) ? $args['heading'] : '';
$content     = isset( $args['content'] ) ? $args['content'] : '';
$footer_text = isset( $args['footer_text'] ) ? $args['footer_text'] : '';

revifoup()->templates->include_template(
	'email/email-header.php',
	array(
		'heading' => $heading,
	)
);

echo wp_kses_post( wpautop( $content ) );

revifoup()->templates->include_template(
	'email/email-footer.php',
	array(
		'footer_text' => $footer_text,
	)
);
