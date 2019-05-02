<?php
list( $rp_path ) = explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) );
$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
if ( isset( $_GET['key'] ) ) {
	$value = sprintf( '%s:%s', wp_unslash( $_GET['login'] ), wp_unslash( $_GET['key'] ) );
	setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
	wp_safe_redirect( remove_query_arg( array( 'key', 'login' ) ) );
	exit;
}

if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
	list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
	$user = check_password_reset_key( $rp_key, $rp_login );
	if ( isset( $_POST['pass1'] ) && ! hash_equals( $rp_key, $_POST['rp_key'] ) ) {
		$user = false;
	}
} else {
	$user = false;
}

if ( ! $user || is_wp_error( $user ) ) {
	setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
	if ( $user && $user->get_error_code() === 'expired_key' ) {
		wp_redirect( site_url( 'wp-login.php?action=lostpassword&error=expiredkey' ) );
	} else {
		wp_redirect( site_url( 'wp-login.php?action=lostpassword&error=invalidkey' ) );
	}
	exit;
}

$errors = new WP_Error();

if ( isset( $_POST['pass1'] ) && $_POST['pass1'] != $_POST['pass2'] ) {
	$errors->add( 'password_reset_mismatch', __( 'The passwords do not match.' ) );
}

/**
 * Fires before the password reset procedure is validated.
 *
 * @since 3.5.0
 *
 * @param object $errors WP Error object.
 * @param WP_User|WP_Error $user WP_User object if the login and reset key match. WP_Error object otherwise.
 */
do_action( 'validate_password_reset', $errors, $user );

if ( ( ! $errors->has_errors() ) && isset( $_POST['pass1'] ) && ! empty( $_POST['pass1'] ) ) {
	reset_password( $user, $_POST['pass1'] );
	setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
	login_header( __( 'Password Reset' ), '<p class="message reset-pass">' . __( 'Your password has been reset.' ) . ' <a href="' . esc_url( wp_login_url() ) . '">' . __( 'Log in' ) . '</a></p>' );
	login_footer();
	exit;
}

wp_enqueue_script( 'utils' );
wp_enqueue_script( 'user-profile' );

clc_login_header( __( 'Reset Password' ), '<p class="message reset-pass">' . __( 'Enter your new password below.' ) . '</p>', $errors );

?>
	<form name="resetpassform" id="resetpassform"
	      action="<?php echo esc_url( network_site_url( 'wp-login.php?action=resetpass', 'login_post' ) ); ?>"
	      method="post" autocomplete="off">
		<input type="hidden" id="user_login" value="<?php echo esc_attr( $rp_login ); ?>" autocomplete="off"/>

		<div class="user-pass1-wrap">
			<p>
				<label for="pass1"><?php _e( 'New password' ); ?></label>
			</p>

			<div class="wp-pwd">
				<div class="password-input-wrapper">
					<input type="password" data-reveal="1"
					       data-pw="<?php echo esc_attr( wp_generate_password( 16 ) ); ?>" name="pass1" id="pass1"
					       class="input password-input" size="24" value="" autocomplete="off"
					       aria-describedby="pass-strength-result"/>
					<span class="button button-secondary wp-hide-pw hide-if-no-js">
					<span class="dashicons dashicons-hidden"></span>
				</span>
				</div>
				<div id="pass-strength-result" class="hide-if-no-js"
				     aria-live="polite"><?php _e( 'Strength indicator' ); ?></div>
			</div>
			<div class="pw-weak">
				<label>
					<input type="checkbox" name="pw_weak" class="pw-checkbox"/>
					<?php _e( 'Confirm use of weak password' ); ?>
				</label>
			</div>
		</div>

		<p class="user-pass2-wrap">
			<label for="pass2"><?php _e( 'Confirm new password' ); ?></label><br/>
			<input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off"/>
		</p>

		<p class="description indicator-hint"><?php echo wp_get_password_hint(); ?></p>
		<br class="clear"/>

		<?php
		/**
		 * Fires following the 'Strength indicator' meter in the user password reset form.
		 *
		 * @since 3.9.0
		 *
		 * @param WP_User $user User object of the user whose password is being reset.
		 */
		do_action( 'resetpass_form', $user );
		?>
		<input type="hidden" name="rp_key" value="<?php echo esc_attr( $rp_key ); ?>"/>
		<p class="submit"><input type="submit" name="wp-submit" id="wp-submit"
		                         class="button button-primary button-large"
		                         value="<?php esc_attr_e( 'Reset Password' ); ?>"/></p>
	</form>

	<p id="nav">
		<a href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in' ); ?></a>
		<?php
		if ( get_option( 'users_can_register' ) ) :
			$registration_url = sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register' ) );

			echo esc_html( $login_link_separator );

			/** This filter is documented in wp-includes/general-template.php */
			echo apply_filters( 'register', $registration_url );
		endif;
		?>
	</p>

<?php
login_footer( 'user_pass' );
