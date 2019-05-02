<?php
check_admin_referer( 'log-out' );

$user = wp_get_current_user();

wp_logout();

if ( ! empty( $_REQUEST['redirect_to'] ) ) {
	$redirect_to = $requested_redirect_to = $_REQUEST['redirect_to'];
} else {
	$redirect_to           = 'wp-login.php?loggedout=true';
	$requested_redirect_to = '';
}

/**
 * Filters the log out redirect URL.
 *
 * @since 4.2.0
 *
 * @param string $redirect_to The redirect destination URL.
 * @param string $requested_redirect_to The requested redirect destination URL passed as a parameter.
 * @param WP_User $user The WP_User object for the user that's logging out.
 */
$redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );
wp_safe_redirect( $redirect_to );
