<?php
if ( ! isset( $_GET['request_id'] ) ) {
	wp_die( __( 'Invalid request.' ) );
}

$request_id = (int) $_GET['request_id'];

if ( isset( $_GET['confirm_key'] ) ) {
	$key    = sanitize_text_field( wp_unslash( $_GET['confirm_key'] ) );
	$result = wp_validate_user_request_key( $request_id, $key );
} else {
	$result = new WP_Error( 'invalid_key', __( 'Invalid key' ) );
}

if ( is_wp_error( $result ) ) {
	wp_die( $result );
}

/**
 * Fires an action hook when the account action has been confirmed by the user.
 *
 * Using this you can assume the user has agreed to perform the action by
 * clicking on the link in the confirmation email.
 *
 * After firing this action hook the page will redirect to wp-login a callback
 * redirects or exits first.
 *
 * @since 4.9.6
 *
 * @param int $request_id Request ID.
 */
do_action( 'user_request_action_confirmed', $request_id );

$message = _wp_privacy_account_request_confirmed_message( $request_id );

clc_login_header( __( 'User action confirmed.' ), $message );
clc_login_footer();