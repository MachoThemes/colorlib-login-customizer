<?php
/**
 * Template Name: Colorlib Login Customizer Template
 *
 * Template to display the WordPress login form in the Customizer.
 * This is essentially a stripped down version of wp-login.php, though not accessible from outside the Customizer.
 *
 */

/** Make sure that the WordPress bootstrap has run before continuing. */
require( ABSPATH . '/wp-load.php' );
if(!is_customize_preview()){
    new Colorlib_Login_Customizer_CSS_Customization();
}

$clc_core = Colorlib_Login_Customizer::instance();
$clc_defaults = $clc_core->get_defaults();
$clc_options = get_option( 'clc-options', array() );
$clc_options = wp_parse_args( $clc_options, $clc_defaults );

// Redirect to HTTPS login if forced to use SSL.
if ( force_ssl_admin() && ! is_ssl() ) {
	if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {
		wp_safe_redirect( set_url_scheme( $_SERVER['REQUEST_URI'], 'https' ) );
		exit();
	} else {
		wp_safe_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		exit();
	}
}

/**
 * Output the login page header.
 *
 * @since 2.1.0
 *
 * @param string $title Optional. WordPress login Page title to display in the `<title>` element.
 *                           Default 'Log In'.
 * @param string $message Optional. Message to display in header. Default empty.
 * @param WP_Error $wp_error Optional. The error to pass. Default is a WP_Error instance.
 */
function clc_login_header( $title = 'Log In', $message = '', $wp_error = null ) {
global $error, $interim_login, $action;

// Don't index any of these forms
add_action( 'login_head', 'wp_sensitive_page_meta' );

add_action( 'login_head', 'clc_wp_login_viewport_meta' );

if ( ! is_wp_error( $wp_error ) ) {
	$wp_error = new WP_Error();
}

// Shake it!
$shake_error_codes = array(
	'empty_password',
	'empty_email',
	'invalid_email',
	'invalidcombo',
	'empty_username',
	'invalid_username',
	'incorrect_password'
);
/**
 * Filters the error codes array for shaking the login form.
 *
 * @since 3.0.0
 *
 * @param array $shake_error_codes Error codes that shake the login form.
 */
$shake_error_codes = apply_filters( 'shake_error_codes', $shake_error_codes );

if ( $shake_error_codes && $wp_error->has_errors() && in_array( $wp_error->get_error_code(), $shake_error_codes ) ) {
	add_action( 'login_head', 'wp_shake_js', 12 );
}

$login_title = get_bloginfo( 'name', 'display' );

/* translators: Login screen title. 1: Login screen name, 2: Network or site name */
$login_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress' ), $title, $login_title );

/**
 * Filters the title tag content for login page.
 *
 * @since 4.9.0
 *
 * @param string $login_title The page title, with extra context added.
 * @param string $title The original page title.
 */
$login_title = apply_filters( 'login_title', $login_title, $title );

if ( is_multisite() ) {
		$login_header_url   = network_home_url();
		$login_header_title = get_network()->site_name;
	} else {
		$login_header_url   = __( 'https://wordpress.org/' );
		$login_header_title = __( 'Powered by WordPress' );
}

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

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'login';

$classes = array( 'login-action-' . $action, 'wp-core-ui' );

include COLORLIB_LOGIN_CUSTOMIZER_BASE .'includes/template-partials/clc-template-header.php'; ?>

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
<div class="ml-container"><div class="ml-extra-div"></div><div class="ml-form-container">
<div id="login">
    <h1>
        <?php if ( is_customize_preview() ) { ?>
               <span id="clc-logo" class="clc-preview-event" data-section="clc_logo"><span
                            class="dashicons dashicons-edit"></span></span>
	    <?php } ?>
        <a id="clc-logo-link" href="<?php echo esc_url( $login_header_url ); ?>"
           title="<?php echo esc_attr( $login_header_title ); ?>" tabindex="-1">
            <span id="logo-text"><?php echo $login_header_text ?></span>
        </a>
    </h1>
	<?php

	unset( $login_header_url, $login_header_title );

	/**
	 * Filters the message to display above the login form.
	 *
	 * @since 2.1.0
	 *
	 * @param string $message Login message text.
	 */
	$message = apply_filters( 'login_message', $message );
	if ( ! empty( $message ) ) {
		echo $message . "\n";
	}

	// In case a plugin uses $error rather than the $wp_errors object.
	if ( ! empty( $error ) ) {
		$wp_error->add( 'error', $error );
		unset( $error );
	}

	if ( $wp_error->has_errors() ) {
		$errors   = '';
		$messages = '';
		foreach ( $wp_error->get_error_codes() as $code ) {
			$severity = $wp_error->get_error_data( $code );
			foreach ( $wp_error->get_error_messages( $code ) as $error_message ) {
				if ( 'message' == $severity ) {
					$messages .= '	' . $error_message . "<br />\n";
				} else {
					$errors .= '	' . $error_message . "<br />\n";
				}
			}
		}
		if ( ! empty( $errors ) ) {
			/**
			 * Filters the error messages displayed above the login form.
			 *
			 * @since 2.1.0
			 *
			 * @param string $errors Login error message.
			 */
			echo '<div id="login_error">' . apply_filters( 'login_errors', $errors ) . "</div>\n";
		}
		if ( ! empty( $messages ) ) {
			/**
			 * Filters instructional messages displayed above the login form.
			 *
			 * @since 2.5.0
			 *
			 * @param string $messages Login messages.
			 */
			echo '<p class="message">' . apply_filters( 'login_messages', $messages ) . "</p>\n";
		}
	}
} // End of clc_login_header()


function clc_login_footer( $input_id = '' ) {
    global $interim_login;
    $clc_core = Colorlib_Login_Customizer::instance();
    $clc_defaults = $clc_core->get_defaults();
    $clc_options = get_option( 'clc-options', array() );
    $clc_options = wp_parse_args( $clc_options, $clc_defaults );

    // Don't allow interim logins to navigate away from the page.
    if ( ! $interim_login ) :
        ?>
        <p id="backtoblog">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <span id="clc-back-to-text">
                    <?php
                    echo '&larr; ';
                    echo (!isset($clc_options['back-to-text']) || '' == $clc_options['back-to-text']) ? __( 'Back to' ) : esc_html($clc_options['back-to-text']) ;
                    ?>
                    </span>
			<?php
			echo esc_html( get_bloginfo( 'title', 'display' ) );
			?>
        </a>
    </p>
        <?php the_privacy_policy_link( '<div class="privacy-policy-page-link">', '</div>' ); ?>
    <?php endif; ?>

    </div>

    <?php if ( ! empty( $input_id ) ) : ?>
    <script type="text/javascript">
    try{document.getElementById('<?php echo $input_id; ?>').focus();}catch(e){}
    if(typeof wpOnload=='function')wpOnload();
    </script>
    <?php endif; ?>

    <?php
    /**
     * Fires in the login page footer.
     *
     * @since 3.1.0
     */
    do_action( 'login_footer' );
    ?>
    <div class="clear"></div>
    </body>
    </html>
    <?php
}

/**
 * Outputs the Javascript to handle the form shaking.
 *
 * @since 3.0.0
 */
function clc_wp_shake_js() {
		?>
        <script type="text/javascript">
            addLoadEvent = function (func) {
                if (typeof jQuery != "undefined") jQuery(document).ready(func); else if (typeof wpOnload != 'function') {
                    wpOnload = func;
                } else {
                    var oldonload = wpOnload;
                    wpOnload = function () {
                        oldonload();
                        func();
                    }
                }
            };

            function s(id, pos) {
                g(id).left = pos + 'px';
            }

            function g(id) {
                return document.getElementById(id).style;
            }

            function shake(id, a, d) {
                c = a.shift();
                s(id, c);
                if (a.length > 0) {
                    setTimeout(function () {
                        shake(id, a, d);
                    }, d);
                } else {
                    try {
                        g(id).position = 'static';
                        wp_attempt_focus();
                    } catch (e) {
                    }
                }
            }

            addLoadEvent(function () {
                var p = new Array(15, 30, 15, 0, -15, -30, -15, 0);
                p = p.concat(p.concat(p));
                var i = document.forms[0].id;
                g(i).position = 'relative';
                shake(i, p, 20);
            });
        </script>
		<?php
	}

/**
 * Outputs the viewport meta tag.
 *
 * @since 3.7.0
 */

function clc_wp_login_viewport_meta() {
?>
  <meta name="viewport" content="width=device-width"/>
<?php
}

/**
 * Handles sending password retrieval email to user.
 *
 * @since 2.5.0
 *
 * @return bool|WP_Error True: when finish. WP_Error on error
 */

function clc_retrieve_password() {
	$errors = new WP_Error();
	if ( empty( $_POST['user_login'] ) || ! is_string( $_POST['user_login'] ) ) {
		$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Enter a username or email address.' ) );
	} elseif ( strpos( $_POST['user_login'], '@' ) ) {
		$user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
		if ( empty( $user_data ) ) {
			$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: There is no account with that username or email address.' ) );
		}
	} else {
		$login     = trim( $_POST['user_login'] );
		$user_data = get_user_by( 'login', $login );
	}

	/**
	 * Fires before errors are returned from a password reset request.
	 *
	 * @since 2.1.0
	 * @since 4.4.0 Added the `$errors` parameter.
	 *
	 * @param WP_Error $errors A WP_Error object containing any errors generated
	 *                         by using invalid credentials.
	 */
	do_action( 'lostpassword_post', $errors );

	if ( $errors->has_errors() ) {
		return $errors;
	}

	if ( ! $user_data ) {
		$errors->add( 'invalidcombo', __( '<strong>ERROR</strong>: There is no account with that username or email address.' ) );

		return $errors;
	}

	// Redefining user_login ensures we return the right case in the email.
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;
	$key        = get_password_reset_key( $user_data );

	if ( is_wp_error( $key ) ) {
		return $key;
	}

	if ( is_multisite() ) {
		$site_name = get_network()->site_name;
	} else {
		/*
		 * The blogname option is escaped with esc_html on the way into the database
		 * in sanitize_option we want to reverse this for the plain text arena of emails.
		 */
		$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}

	$message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
	/* translators: %s: site name */
	$message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
	/* translators: %s: user login */
	$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
	$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
	$message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
	$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

	/* translators: Password reset email subject. %s: Site name */
	$title = sprintf( __( '[%s] Password Reset' ), $site_name );

	/**
	 * Filters the subject of the password reset email.
	 *
	 * @since 2.8.0
	 * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
	 *
	 * @param string $title Default email title.
	 * @param string $user_login The username for the user.
	 * @param WP_User $user_data WP_User object.
	 */
	$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

	/**
	 * Filters the message body of the password reset mail.
	 *
	 * If the filtered message is empty, the password reset email will not be sent.
	 *
	 * @since 2.8.0
	 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
	 *
	 * @param string $message Default mail message.
	 * @param string $key The activation key.
	 * @param string $user_login The username for the user.
	 * @param WP_User $user_data WP_User object.
	 */
	$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

	if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
		wp_die( __( 'The email could not be sent.' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function.' ) );
	}

	return true;
}

//
// Main.
//
/**
 * Fires when the login form is initialized.
 *
 * @since 3.2.0
 */
do_action( 'login_init' );

/**
 * Filters the login page body classes.
 *
 * @since 3.5.0
 *
 * @param array $classes An array of body classes.
 * @param string $action The action that brought the visitor to the login page.
 */

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'login';
$errors = new WP_Error();
if ( isset( $_GET['key'] ) ) {
	$action = 'resetpass';
}

// Validate action so as to default to the login screen.
if ( ! in_array( $action, array(
		'postpass',
		'logout',
		'lostpassword',
		'retrievepassword',
		'resetpass',
		'rp',
		'register',
		'login',
		'confirmaction'
	), true ) && false === has_filter( 'login_form_' . $action ) ) {
		$action = 'login';
    }

nocache_headers();

header( 'Content-Type: ' . get_bloginfo( 'html_type' ) . '; charset=' . get_bloginfo( 'charset' ) );

if ( defined( 'RELOCATE' ) && RELOCATE ) { // Move flag is set
	if ( isset( $_SERVER['PATH_INFO'] ) && ( $_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF'] ) ) {
		$_SERVER['PHP_SELF'] = str_replace( $_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF'] );
	}
	$url = dirname( set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ) );
	if ( $url != get_option( 'siteurl' ) ) {
		update_option( 'siteurl', $url );
	}
}

//Set a cookie now to see if they are supported by the browser.
$secure = ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) );
setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN, $secure );
if ( SITECOOKIEPATH != COOKIEPATH ) {
	setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
}


/**
 * Fires before a specified login form action.
 *
 * The dynamic portion of the hook name, `$action`, refers to the action
 * that brought the visitor to the login form. Actions include 'postpass',
 * 'logout', 'lostpassword', etc.
 *
 * @since 2.8.0
 */
do_action( "login_form_{$action}" );

$http_post     = ( 'POST' == $_SERVER['REQUEST_METHOD'] );
$interim_login = isset( $_REQUEST['interim-login'] );

/**
 * Filters the separator used between login form navigation links.
 *
 * @since 4.9.0
 *
 * @param string $login_link_separator The separator used between login form navigation links.
 */

$login_link_separator = apply_filters( 'login_link_separator', ' | ' );


switch ( $action ) {
case 'logout':
    include COLORLIB_LOGIN_CUSTOMIZER_BASE .'includes/login-actions/clc-template-logout-action.php';
	exit();

case 'lostpassword':
case 'retrievepassword':
	include COLORLIB_LOGIN_CUSTOMIZER_BASE .'includes/login-actions/clc-template-retrieve-password.php';
	break;

case 'resetpass':
case 'rp':
	include COLORLIB_LOGIN_CUSTOMIZER_BASE .'includes/login-actions/clc-template-reset-password.php';
	break;

case 'register':
	include COLORLIB_LOGIN_CUSTOMIZER_BASE .'includes/login-actions/clc-template-register.php';
	break;

case 'confirmaction':
	include COLORLIB_LOGIN_CUSTOMIZER_BASE .'includes/login-actions/clc-template-confirmation.php';
	exit;

case 'login':
default:
	include COLORLIB_LOGIN_CUSTOMIZER_BASE .'includes/login-actions/clc-template-login.php';
    break;
} // End action switch.
