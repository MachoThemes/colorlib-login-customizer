<?php
/**
 * Template Name: Colorlib Login Customizer Template Preview
 *
 * Template to display the WordPress login form in the Customizer.
 * This is essentially a stripped down version of wp-login.php, though not accessible from outside the Customizer.
 *
 */

require( ABSPATH . '/wp-load.php' );
$clc_core = Colorlib_Login_Customizer::instance();
$clc_defaults = $clc_core->get_defaults();
$clc_options = get_option( 'clc-options', array() );
$clc_options = wp_parse_args( $clc_options, $clc_defaults );
/**
 * Output the login page header.
 *
 * @param string $title Optional. WordPress login Page title to display in the `<title>` element.
 *                           Default 'Log In'.
 * @param string $message Optional. Message to display in header. Default empty.
 * @param WP_Error $wp_error Optional. The error to pass. Default empty.
 */
function clc_login_header( $title = 'Log In', $message = '', $wp_error = '' ) {

global $error, $action;

if ( empty( $wp_error ) ) {
	$wp_error = new WP_Error();
}

$login_title = get_bloginfo( 'name', 'display' );

/* translators: Login screen title. 1: Login screen name, 2: Network or site name */
$login_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress', 'colorlib-login-customizer' ), $title, $login_title );
/**
 * Filters the title tag content for login page.
 *
 * @since 4.9.0
 *
 * @param string $login_title The page title, with extra context added.
 * @param string $title The original page title.
 */
$login_title = apply_filters( 'login_title', $login_title, $title );
?><!DOCTYPE html>
<head>
    <title><?php echo esc_attr( $login_title ); ?></title>
	<?php
	wp_enqueue_style( 'login' );

	/**
	 * Enqueue scripts and styles for the login page.
	 *
	 * @since 3.1.0
	 */
	do_action( 'login_enqueue_scripts' );

	/**
	 * Fires in the login page header after scripts are enqueued.
	 *
	 * @since 2.1.0
	 */
	do_action( 'login_head' );
	?>
</head>

<?php
}

/**
 * Fires when the login form is initialized.
 *
 * @since 3.2.0
 */
do_action( 'login_init' );

/**
 * Fires before a specified login form action.
 *
 * The dynamic portion of the hook name, `$action`, refers to the action
 * that brought the visitor to the login form. Actions include 'postpass',
 * 'logout', 'lostpassword', etc.
 *
 * @since 2.8.0
 */
do_action( 'login_form_login' );
do_action( 'login_form_register' );

/**
 * Filters the separator used between login form navigation links.
 */
$login_link_separator = apply_filters( 'login_link_separator', ' | ' );

/**
 * Filters the login page errors.
 *
 * @since 3.6.0
 *
 * @param object $errors WP Error object.
 * @param string $redirect_to Redirect destination URL.
 */
clc_login_header( __( 'Log In', 'colorlib-login-customizer' ), '', '' );

$login_header_url   = __( 'https://wordpress.org/', 'colorlib-login-customizer' );
$login_header_title = __( 'Powered by WordPress', 'colorlib-login-customizer' );

/**
 * Filters link URL of the header logo above login form.
 *
 * @since 2.1.0
 *
 * @param string $login_header_url Login header logo URL.
 */
$login_header_url = apply_filters( 'login_headerurl', $login_header_url );

/**
 * Filters the title attribute of the header logo above login form.
 *
 * @since 2.1.0
 *
 * @param string $login_header_title Login header logo title attribute.
 */
$login_header_title = apply_filters( 'login_headertitle', $login_header_title );

/*
 * To match the URL/title set above, Multisite sites have the blog name,
 * while single sites get the header title.
 */
if ( is_multisite() ) {
	$login_header_text = get_bloginfo( 'name', 'display' );
} else {
	$login_header_text = $login_header_title;
}

/**
 * Filters the login page body classes.
 *
 * @since 3.5.0
 *
 * @param array $classes An array of body classes.
 * @param string $action The action that brought the visitor to the login page.
 */
$classes   = array( 'login-action-login', 'wp-core-ui' );
$classes[] = ' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_locale() ) ) );
$classes   = apply_filters( 'login_body_class', $classes, 'login' );
?>

<body class="login <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
<?php if ( is_customize_preview() ) { ?>
    <div class="clc-general-actions">
        <div id="clc-templates" class="clc-preview-event" data-section="clc_templates"><span
                    class="dashicons dashicons-tagcloud"></span></div>
        <div id="clc-layout" class="clc-preview-event" data-section="clc_layout"><span
                    class="dashicons dashicons-layout"></span></div>
        <div id="clc-background" class="clc-preview-event" data-section="clc_background"><span
                    class="dashicons dashicons-admin-customizer"></span></div>
    </div>
<?php } ?>
<?php
/**
 * Fires in the login page header after the body tag is opened.
 *
 * @since 4.6.0
 */
do_action( 'login_header' );
?>

<div id="login">

    <h1>
        <a id="clc-logo-link" href="<?php echo esc_url( $login_header_url ); ?>"
           title="<?php echo esc_attr( $login_header_title ); ?>" tabindex="-1">
            <span id="clc-logo" class="clc-preview-event" data-section="clc_logo"><span
                        class="dashicons dashicons-edit"></span></span>
            <span id="logo-text"><?php echo $login_header_text ?></span>
        </a>
    </h1>
    <form name="loginform" class="show-only_login" id="loginform"
          action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
		<?php if ( is_customize_preview() ) { ?>
            <div id="clc-loginform" class="clc-preview-event" data-section="clc_form"><span
                        class="dashicons dashicons-edit"></span></div>
		<?php } ?>
        <p>
            <label for="user_login"><span id="clc-username-label">
                            <?php if ( is_customize_preview() ) {
	                            _e( 'Username or Email Address', 'colorlib-login-customizer' );
                            } else {
	                            echo wp_kses_post( $clc_options['username-label'] );
                            } ?></span><br/>
                <input type="text" name="log" id="user_login" class="input"
                       value="<?php echo ( is_customize_preview() ) ? esc_attr( $user_login ) : ''; ?>"
                       size="20"/></label>
        </p>
        <p>
            <label for="user_pass"><span id="clc-password-label">
                            <?php
                            if ( is_customize_preview() ) {
	                            _e( 'Password', 'colorlib-login-customizer' );
                            } else {
	                            echo wp_kses_post( $clc_options['password-label'] );
                            }
                            ?></span><br/>
                <input type="password" name="pwd" id="user_pass" class="input" value="" size="20"/></label>
        </p>
		<?php
		/**
		 * Fires following the 'Password' field in the login form.
		 *
		 * @since 2.1.0
		 */
		do_action( 'login_form' );
		?>
        <p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme"
                                                              value="forever"/> <span
                        id="clc-rememberme-label"><?php ( is_customize_preview() ) ? esc_html_e( 'Remember Me', 'colorlib-login-customizer' ) : esc_attr_e( $clc_options['rememberme-label'] ); ?></span></label>
        </p>
        <p class="submit">
            <input type="submit" name="wp-submit" class="button button-primary button-large"
                   value="<?php ( is_customize_preview() ) ? esc_attr_e( 'Log In', 'colorlib-login-customizer' ) : esc_attr_e( $clc_options['login-label'] ); ?>"/>
            <input type="hidden" value="1" name="testcookie">
            <input type="hidden" value="<?php echo admin_url(); ?>" name="redirect_to">
        </p>
    </form>

    <form name="registerform" style="display:none" class="show-only_register" id="registerform"
          action="<?php echo esc_url( wp_registration_url() ); ?>" method="post">
        <p>
            <label for="user_register"><span
                        id="clc-register-sername-label"><?php _e( 'Username', 'colorlib-login-customizer' ); ?></span><br/>
                <input type="text" name="log" id="user_register" class="input"
                       value="<?php echo esc_attr( $user_login ); ?>" size="20"/></label>
        </p>
        <p>
            <label for="user_email"><span
                        id="clc-register-email-label"><?php _e( 'Email', 'colorlib-login-customizer' ); ?></span><br/>
                <input type="email" name="email" id="user_email" class="input" value="" size="20"/></label>
        </p>
		<?php
		/**
		 * Fires following the 'Password' field in the login form.
		 *
		 * @since 2.1.0
		 */
		do_action( 'login_form' );
		?>
        <p id="reg_passmail"><?php _e( 'Registration confirmation will be emailed to you.', 'colorlib-login-customizer' ); ?></p>
        <p class="submit"><input type="submit" name="wp-submit" class="button button-primary button-large"
                                 value="<?php esc_attr_e( 'Register', 'colorlib-login-customizer' ); ?>"/></p>
    </form>

    <form style="display:none;" class="show-only_lostpassword" name="lostpasswordform" id="lostpasswordform" action=""
          method="post">
        <p>
            <label for="user_login"><span><?php (is_customize_preview()) ? __( 'Username or Email Address', 'colorlib-login-customizer' ) : wp_kses_post($clc_options['username-label']); ?></span><br/>
                <input type="text" name="user_login" id="user_login" class="input"
                       value="<?php echo (is_customize_preview()) ? esc_attr( $user_login ): '' ; ?>" size="20" autocapitalize="off"/></label>
        </p>
		<?php
		/**
		 * Fires inside the lostpassword form tags, before the hidden fields.
		 *
		 * @since 2.1.0
		 */
		do_action( 'lostpassword_form' );
		?>
        <p class="submit"><input type="submit" name="wp-submit" id="wp-submit"
                                 class="button button-primary button-large"
                                 value="<?php echo (is_customize_preview()) ? esc_attr__( 'Get New Password', 'colorlib-login-customizer' ) : wp_kses_post($clc_options['lostpassword-button-label']); ?>"/></p>
    </form>

    <p id="nav">
		<?php
		if ( get_option( 'users_can_register' ) ) :
			$registration_url = sprintf( '<a id="register-link-label" href="%s" class="show-only_login show-only_lostpassword">%s</a>', esc_url( wp_registration_url() ), $clc_options['register-link-label'] );


			/** This filter is documented in wp-includes/general-template.php */
			echo apply_filters( 'register', $registration_url );

			echo '<span style="display:none" class="show-only_lostpassword">' . esc_html( $login_link_separator ) . '</span>';

			echo '<a href="#" id="login-link-label" class="show-only_register show-only_lostpassword" style="display:none">' . $clc_options['login-link-label'] . '</a>';

			echo '<span class="show-only_register show-only_login">' . esc_html( $login_link_separator ) . '</span>';
		endif;
		?>
        <a class="show-only_register show-only_login" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"
           id="clc-lost-password-text"><?php echo ( is_customize_preview() ) ? __( 'Lost your password?', 'colorlib-login-customizer' ) : wp_kses_post( $clc_options['lost-password-text'] ); ?></a>
    </p>
    <p id="backtoblog">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <span id="clc-back-to-text">
                    <?php
                    echo '&larr; ';
                    _e( 'Back to', 'colorlib-login-customizer' );
                    ?>
                    </span>
			<?php
			echo esc_html( get_bloginfo( 'title', 'display' ) );
			?>
        </a>
    </p>
</div>

<?php do_action( 'login_footer' ); ?>
<div class="clear"></div>
<?php
wp_footer();
?>
</body>

</html>
