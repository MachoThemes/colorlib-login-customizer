<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
};

class Colorlib_Login_Customizer {

	/**
	 * The single instance of Colorlib_Login_Customizer.
	 *
	 * @var    object
	 * @access   private
	 * @since    1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 *
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token   = 'colorlib-login-customizer';
		$this->base     = 'clc_';
		$this->key_name = 'clc-options';

		// Load plugin environment variables
		$this->file       = $file;
		$this->dir        = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		// Remove this after Grunt
		$this->script_suffix = '';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		add_action( 'admin_init', array( $this, 'redirect_customizer' ) );

		add_action( 'init', array($this,'clc_redirect_on_login' ));

		// Load customizer settings
		add_action( 'customize_register', array( $this, 'load_customizer' ), 10, 1 );

		add_filter( 'template_include', array( $this, 'change_template_if_necessary' ), 99 );

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );

		// Generate plugins css
		add_action( 'init', array( $this, 'load_customizer_css' ) );

	} // End __construct ()

	/**
	 * Load the customizer controls
	 *
	 * @param $manager
	 */
	public function load_customizer( $manager ) {
		new Colorlib_Login_Customizer_Customizer( $this, $manager );
	}

	public function load_customizer_css() {
		new Colorlib_Login_Customizer_CSS_Customization();
	}

	/**
	 * Hook to redirect the page for the Customizer.
	 *
	 * @access public
	 * @return void
	 */
	public function redirect_customizer() {
		if ( ! empty( $_GET['page'] ) ) { // Input var okay.
			if ( 'colorlib-login-customizer_settings' === $_GET['page'] ) { // Input var okay.

				// Generate the redirect url.
				$url = add_query_arg(
					array(
						'autofocus[panel]' => 'clc_main_panel',
					),
					admin_url( 'customize.php' )
				);

				wp_safe_redirect( $url );
			}
		}
	}

	/**
	 * Redirect login page to plugin login template
	 */
	public function clc_redirect_on_login() {
		global $pagenow;
		if(!is_user_logged_in()) {
			if ( 'wp-login.php' == $pagenow ) {
				$file = plugin_dir_path( __FILE__ ) . 'login-template.php';
				include( $file );

				exit();
			}
		}
	}


	/**
	 * Load plugin localisation
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation() {
		load_plugin_textdomain( 'colorlib-login-customizer', false, dirname( plugin_basename( $this->file ) ) . '/languages/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
		$domain = 'colorlib-login-customizer';

		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/languages/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Colorlib_Login_Customizer Instance
	 *
	 * Ensures only one instance of Colorlib_Login_Customizer is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see   Colorlib_Login_Customizer()
	 * @return Main Colorlib_Login_Customizer instance
	 */
	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}

		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'colorlib-login-customizer' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'colorlib-login-customizer' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();

		// Backward compatibility
		$options = get_option( $this->key_name, array() );
		if ( isset( $options['templates'] ) && '01' == $options['templates'] ) {
			$options['templates'] = 'default';
			$options['columns']   = 2;
			update_option( $this->key_name, $options );
		}
	} // End install ()

	/**
	 * Log the plugin version number.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()


	// Let's hack a little bit
	public function change_template_if_necessary( $template ) {
		if ( is_customize_preview() && isset( $_REQUEST['colorlib-login-customizer-customization'] )  && is_user_logged_in() ) {

			$new_template = plugin_dir_path( __FILE__ ) . 'login-template.php';
			return $new_template;
		}

		return $template;
	}

	public function get_defaults() {
		return array(
			/**
			 * Templates
			 */
			'templates'                    => 'default',
			/**
			 * Layout
			 */
			'columns'                      => '1',
			'columns-width'                => array(
				'left'  => 6,
				'right' => 6,
			),
			'form-column-align'            => '3',
			'form-vertical-align'          => '2',
			/**
			 * Logo section
			 */
			'hide-logo'                    => 0,
			'use-text-logo'                => 0,
			'logo-url'                     => site_url(),
			'custom-logo'                  => '',
			'logo-text-color'              => '#444',
			'logo-text-size'               => '20',
			'logo-text-color-hover'        => '#00a0d2',
			'logo-width'                   => '',
			'logo-height'                  => '',
			/**
			 * Background section
			 */
			'custom-background'            => '',
			'custom-background-form'       => '',
			'custom-background-color'      => '',
			'custom-background-color-form' => '',
			/**
			 * Form section
			 */
			'form-width'                   => '',
			'form-height'                  => '',
			'form-background-image'        => '',
			'form-background-color'        => '#fff',
			'form-padding'                 => '',
			'form-border'                  => '',
			'form-border-radius'           => '',
			'form-shadow'                  => '',
			'form-field-width'             => '',
			'form-field-margin'            => '',
			'form-field-border-radius'     => 'unset',
			'form-field-border'            => '1px solid #ddd',
			'form-field-background'        => '',
			'form-field-color'             => '',
			'username-label'               => 'Username or Email Address',
			'password-label'               => 'Password',
			'rememberme-label'             => 'Remember Me',
			'lost-password-text'           => 'Lost your password?',
			'back-to-text'                 => '&larr; Back to %s',
			'register-link-label'          => 'Register',

			'login-label'                 => 'Log In',
			'form-label-color'            => '',
			'hide-extra-links'            => false,
			/**
			 * Registration section
			 */
			'register-username-label'     => 'Username',
			'register-email-label'        => 'Email',
			'register-button-label'       => 'Register',
			'register-confirmation-email' => 'Registration confirmation will be emailed to you.',
			'login-link-label'            => 'Log in',
			/**
			 * Lost Password
			 */
			'lostpassword-username-label' => 'Username or Email Address',
			'lostpassword-button-label'   => 'Get New Password',
			/**
			 * Others section ( misc )
			 */
			'button-background'           => '',
			'button-background-hover'     => '',
			'button-border-color'         => '',
			'button-border-color-hover'   => '',
			'button-shadow'               => '',
			'button-text-shadow'          => '',
			'button-color'                => '',
			'link-color'                  => '',
			'link-color-hover'            => '',
			'hide-rememberme'             => false,
			/**
			 * Custom CSS
			 */
			'custom-css'                  => '',
			/**
			 * Reset value is not dynamic
			 */
			'initial'                     => 'initial',
		);
	}
}
