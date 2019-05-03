<?php
if ( $http_post ) {
	$errors = retrieve_password();
	if ( ! is_wp_error( $errors ) ) {
		$redirect_to = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : 'wp-login.php?checkemail=confirm';
		wp_safe_redirect( $redirect_to );
		exit();
	}
}

if ( isset( $_GET['error'] ) ) {
	if ( 'invalidkey' == $_GET['error'] ) {
		$errors->add( 'invalidkey', __( 'Your password reset link appears to be invalid. Please request a new link below.' ) );
	} elseif ( 'expiredkey' == $_GET['error'] ) {
		$errors->add( 'expiredkey', __( 'Your password reset link has expired. Please request a new link below.' ) );
	}
}

$lostpassword_redirect = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
/**
 * Filters the URL redirected to after submitting the lostpassword/retrievepassword form.
 *
 * @since 3.0.0
 *
 * @param string $lostpassword_redirect The redirect destination URL.
 */
$redirect_to = apply_filters( 'lostpassword_redirect', $lostpassword_redirect );

/**
 * Fires before the lost password form.
 *
 * @since 1.5.1
 * @since 5.1.0 Added the `$errors` parameter.
 *
 * @param WP_Error $errors A `WP_Error` object containing any errors generated by using invalid
 *                         credentials. Note that the error object may not contain any errors.
 */
do_action( 'lost_password', $errors );

clc_login_header( __( 'Lost Password' ), '<p class="message">' . __( 'Please enter your username or email address. You will receive a link to create a new password via email.' ) . '</p>', $errors );

$user_login = '';

if ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) {
	$user_login = wp_unslash( $_POST['user_login'] );
}

?>

	<form name="lostpasswordform" id="lostpasswordform"
	      action="<?php echo esc_url( network_site_url( 'wp-login.php?action=lostpassword', 'login_post' ) ); ?>"
	      method="post">
		<p>
			<label for="user_login"><?php echo ( is_customize_preview() ) ? __( 'Username or Email Address', 'colorlib-login-customizer' ) : esc_html( $clc_options['lostpassword-username-label'] ); ?>
				<br/>
				<input type="text" name="user_login" id="user_login" class="input"
				       value="<?php echo esc_attr( $user_login ); ?>" size="20" autocapitalize="off"/></label>
		</p>
		<?php
		/**
		 * Fires inside the lostpassword form tags, before the hidden fields.
		 *
		 * @since 2.1.0
		 */
		do_action( 'lostpassword_form' );
		?>
		<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>"/>
		<p class="submit"><input type="submit" name="wp-submit" id="wp-submit"
		                         class="button button-primary button-large"
		                         value="<?php echo ( is_customize_preview() ) ? esc_attr__( 'Get New Password', 'colorlib-login-customizer' ) : esc_html( $clc_options['lostpassword-button-label'] ); ?>"/>
		</p>
	</form>

	<p id="nav">
		<a href="<?php echo esc_url( wp_login_url() ); ?>"><?php echo (is_customize_preview() && !isset($clc_options['login-link-label'])) ?  __( 'Log in' ) : esc_html($clc_options['login-link-label']); ?></a>
		<?php
		$register_link_text = (is_customize_preview() && !isset($clc_options['register-link-label'])) ? __('Register') : esc_html($clc_options['register-link-label']);
		if ( get_option( 'users_can_register' ) ) :
			$registration_url = sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), $register_link_text );

			echo esc_html( $login_link_separator );

			/** This filter is documented in wp-includes/general-template.php */
			echo apply_filters( 'register', $registration_url );
		endif;
		?>
	</p>

<?php
if(!is_customize_preview()){
	login_footer( 'user_login' );
}
