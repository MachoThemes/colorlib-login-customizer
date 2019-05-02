<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Colorlib_Login_Customizer_Customization
 */
class Colorlib_Login_Customizer_CSS_Customization {
	/**
	 * @var array
	 */
	private $options = array();
	/**
	 * @var array
	 */
	private $selectors = array();
	/**
	 * @var string
	 */
	private $base = '';

	private $defaults;

	/**
	 * Colorlib_Login_Customizer_CSS_Customization constructor.
	 */
	public function __construct() {
		$plugin         = Colorlib_Login_Customizer::instance();
		$this->key_name = $plugin->key_name;
		$this->defaults = $plugin->get_defaults();
		$this->set_options();

		add_action( 'login_init', array( $this, 'check_general_texts' ) );
		add_action( 'login_form_login', array( $this, 'check_login_texts' ) );
		add_action( 'login_form_register', array( $this, 'check_register_texts' ) );
		add_action( 'login_form_lostpassword', array( $this, 'check_lostpasswords_texts' ) );
		add_action( 'login_head', array( $this, 'generate_css' ), 15 );
		add_filter( 'login_body_class', array( $this, 'body_class' ) );
		add_filter( 'login_headerurl', array( $this, 'logo_url' ), 99 );
		add_filter('login_headertitle', array($this, 'logo_title'), 99);
		add_action( 'customize_preview_init', array( $this, 'output_css_object' ), 26 );
	}

	private function generate_name( $id ) {
		return $this->key_name . '[' . $id . ']';
	}

	public function output_css_object() {

		$css_object = array(
			'selectors' => array(),
			'settings' => array(),
		);

		foreach ( $this->selectors as $selector => $settings ) {
			$css_object['selectors'][ $selector ] = $settings['options'];
			foreach ( $settings['options'] as $index => $setting ) {
				$css_object['settings'][ $setting ] = array(
					'name' => $this->generate_name( $setting ),
					'value' => $this->options[ $setting ],
					'attribute' => $settings['attributes'][ $index ],
				);
			}
		}

		wp_localize_script( 'colorlib-login-customizer-preview', 'CLC', $css_object );

	}

	/**
	 * Set the options array, it returns nothing
	 */
	public function set_options() {

		$options = get_option( $this->key_name, array() );
		$this->options = wp_parse_args( $options, $this->defaults );

		$this->selectors = array(
			'.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover' => array(
				'attributes' => array(
					'background',
					'border-color',
				),
				'options' => array(
					'button-background-hover',
					'button-border-color-hover',
				),
			),
			'.wp-core-ui .button-primary' => array(
				'attributes' => array(
					'background',
					'border-color',
					'box-shadow',
					'text-shadow',
					'color',
				),
				'options' => array(
					'button-background',
					'button-border-color',
					'button-shadow',
					'button-text-shadow',
					'button-color',
				),
			),
			'.login #backtoblog a, .login #nav a' => array(
				'attributes' => array(
					'color',
				),
				'options' => array(
					'link-color',
				),
			),
			'.login #backtoblog a:hover, .login #nav a:hover, .login h1 a:hover' => array(
				'attributes' => array(
					'color',
				),
				'options' => array(
					'link-color-hover',
				),
			),
			'.ml-container #login' => array(
				'attributes' => array(
					'max-width',
				),
				'options' => array(
					'form-width',
				),
			),
			'#loginform,#registerform,#lostpasswordform' => array(
				'attributes' => array(
					'height',
					'background-image',
					'background-color',
					'padding',
					'border',
					'border-radius',
					'box-shadow',
				),
				'options' => array(
					'form-height',
					'form-background-image',
					'form-background-color',
					'form-padding',
					'form-border',
					'form-border-radius',
					'form-shadow',
				),
			),
			'.login form .input, .login input[type="text"]' => array(
				'attributes' => array(
					'max-width',
					'margin',
					'border-radius',
					'border',
					'background',
					'color',
				),
				'options' => array(
					'form-field-width',
					'form-field-margin',
					'form-field-border-radius',
					'form-field-border',
					'form-field-background',
					'form-field-color',
				),
			),
			'.login label' => array(
				'attributes' => array(
					'color',
				),
				'options' => array(
					'form-label-color',
				),
			),
			'.ml-container .ml-extra-div' => array(
				'attributes' => array(
					'background-image',
					'background-color',
				),
				'options' => array(
					'custom-background',
					'custom-background-color',
				),
			),
			'.ml-container .ml-form-container' => array(
				'attributes' => array(
					'background-image',
					'background-color',
				),
				'options' => array(
					'custom-background-form',
					'custom-background-color-form',
				)
			),
			'.login h1 a' => array(
				'attributes' => array(
					'background-image',
					'width',
					'height',
				),
				'options' => array(
					'custom-logo',
					'logo-width',
					'logo-height',
				),
			),
			'.login.clc-text-logo h1 a' => array(
				'attributes' => array(
					'color',
					'font-size',
				),
				'options' => array(
					'logo-text-color',
					'logo-text-size',
				),
			),
			'.login.clc-text-logo h1 a:hover' => array(
				'attributes' => array(
					'color',
				),
				'options' => array(
					'logo-text-color-hover',
				),
			),
			'#login > h1' => array(
				'attributes' => array(
					'display',
				),
				'options' => array(
					'hide-logo',
				),
			),
			'#login > #nav,#login > #backtoblog' => array(
				'attributes' => array(
					'display',
				),
				'options' => array(
					'hide-extra-links',
				),
			),
			'#login form .forgetmenot' => array(
				'attributes' => array(
					'display',
				),
				'options' => array(
					'hide-rememberme',
				),
			),
		);

	}

	/**
	 * Create the CSS string for output
	 *
	 * @return mixed|string
	 */
	public function create_css() {
		/**
		 * Get an instance of the plugin so we can get the token
		 */
		//$instance = Colorlib_Login_Customizer::instance();

		$string = '';
		/**
		 * In case the array is empty, we return an empty string
		 */
		if ( empty( $this->options ) ) {
			return $string;
		}

		/**
		 * Start building the CSS file
		 */
		$string .= $this->_set_background_options();
		$string .= $this->_set_logo_options();
		$string .= $this->_set_form_options();
		$string .= $this->_set_miscellaneous_options();

		return $string;
	}

	/**
	 * @return string
	 */
	public function _set_miscellaneous_options() {
		$string = '';

		$string .= $this->create_css_lines(
			'.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover',
			array(
				'background',
				'border-color',
			),
			array(
				'button-background-hover',
				'button-border-color-hover',
			)
		);

		$string .= $this->create_css_lines(
			'.wp-core-ui .button-primary',
			array(
				'background',
				'border-color',
				'box-shadow',
				'text-shadow',
				'color',
			),
			array(
				'button-background',
				'button-border-color',
				'button-shadow',
				'button-text-shadow',
				'button-color',
			)
		);

		$string .= $this->create_css_lines(
			'.login #backtoblog a, .login #nav a',
			array(
				'color',
			),
			array(
				'link-color',
			)
		);

		$string .= $this->create_css_lines(
			'.login #backtoblog a:hover, .login #nav a:hover, .login h1 a:hover',
			array(
				'color',
			),
			array(
				'link-color-hover',
			)
		);

		$string .= $this->create_css_lines(
			'#login form .forgetmenot',
			array(
				'display',
			),
			array(
				'hide-rememberme',
			)
		);

		return $string;
	}

	/**
	 * @return string
	 */
	public function _set_form_options() {
		$string = '';

		$string .= $this->create_css_lines(
			'.ml-container #login',
			array(
				'max-width',
			),
			array(
				'form-width',
			)
		);

		/**
		 * Set form variables
		 */
		$string .= $this->create_css_lines(
			'#loginform,#registerform,#lostpasswordform',
			array(
				'height',
				'background-image',
				'background-color',
				'padding',
				'border',
				'border-radius',
				'box-shadow',
			),
			array(
				'form-height',
				'form-background-image',
				'form-background-color',
				'form-padding',
				'form-border',
				'form-border-radius',
				'form-shadow',
			)
		);

		/**
		 * Set form field variables
		 */
		$string .= $this->create_css_lines(
			'.login form .input, .login input[type="text"]',
			array(
				'max-width',
				'margin',
				'border-radius',
				'border',
				'background',
				'color',
			),
			array(
				'form-field-width',
				'form-field-margin',
				'form-field-border-radius',
				'form-field-border',
				'form-field-background',
				'form-field-color',
			)
		);

		/**
		 * Set form field labels
		 */
		$string .= $this->create_css_lines(
			'.login label',
			array(
				'color',
			),
			array(
				'form-label-color',
			)
		);

		$string .= $this->create_css_lines(
			'#login > #nav,#login > #backtoblog',
			array(
				'display',
			),
			array(
				'hide-extra-links',
			)
		);

		return $string;
	}

	/**
	 * @return string
	 */
	public function _set_background_options() {
		$string = '';
		/**
		 * Set background-image
		 */
		$string .= $this->create_css_lines(
			'.ml-container .ml-extra-div',
			array(
				'background-image',
				'background-color',
			),
			array(
				'custom-background',
				'custom-background-color',
			)
		);

		$string .= $this->create_css_lines(
			'.ml-container .ml-form-container',
			array(
				'background-image',
				'background-color',
			),
			array(
				'custom-background-form',
				'custom-background-color-form',
			)
		);

		return $string;

	}

	/**
	 * @return string
	 */
	public function _set_logo_options() {
		$string = '';
		/**
		 * Set logo dimensions
		 */
		$string .= $this->create_css_lines(
			'.login h1 a',
			array(
				'background-image',
				'background-size',
				'width',
				'height',
			),
			array(
				'custom-logo',
				'logo-width',
				'logo-width',
				'logo-height',
			)
		);

		$string .= $this->create_css_lines(
			'.login.clc-text-logo h1 a',
			array(
				'color',
				'font-size',
			),
			array(
				'logo-text-color',
				'logo-text-size',
			)
		);

		$string .= $this->create_css_lines(
			'.login.clc-text-logo h1 a:hover',
			array(
				'color',
			),
			array(
				'logo-text-color-hover',
			)
		);

		$string .= $this->create_css_lines(
			'#login > h1',
			array(
				'display',
			),
			array(
				'hide-logo',
			)
		);

		return $string;
	}

	/**
	 * @param $selector
	 * @param $properties
	 * @param $options
	 *
	 * @return string
	 */
	private function create_css_lines( $selector, $properties, $options ) {
		$string = '';
		$valued = array();

		$i = 0;
		foreach ( $options as $option ) {
			if ( ! empty( $this->options[ $option ] ) ) {
				$valued[ $properties[ $i ] ] = $this->options[ $option ];
			}
			$i ++;
		}

		if ( ! empty( $valued ) ) {
			$string .= $selector . '{' . "\n";

			foreach ( $valued as $index => $value ) {
				$string .= $index . ':' . esc_attr( $this->add_artifacts( $index, $value ) ) . ';' . "\n";
			}
			$string .= '}' . "\n";
		}

		return $string;
	}

	/**
	 *
	 *
	 * @param $property
	 * @param $value
	 *
	 * @return string
	 */
	private function add_artifacts( $property, $value ) {
		switch ( $property ) {
			case 'background-image':
				$value = 'url(' . $value . ')';
				break;

			case 'width':
			case 'min-width':
			case 'max-width':
			case 'background-size':
			case 'height':
			case 'min-height':
			case 'max-height':
			case 'font-size':
				$value = $value . 'px';
				break;
			case 'display':
				if ( ! $value ) {
					$value = 'block';
				}else{
					$value = 'none';
				}
			default:
				break;
		}

		return $value;
	}

	public function body_class( $classes ) {

		if ( '2' == $this->options['columns'] ) {
			$classes[] = 'ml-half-screen';
			if ( isset( $this->options['form-column-align'] ) ) {
				$classes[] = 'ml-login-align-' . esc_attr( $this->options['form-column-align'] );
			}
		}

		if ( isset( $this->options['form-vertical-align'] ) ) {
			$classes[] = 'ml-login-vertical-align-' . esc_attr( $this->options['form-vertical-align'] );
		}

		if ( isset( $this->options['form-horizontal-align'] ) ) {
			$classes[] = 'ml-login-horizontal-align-' . esc_attr( $this->options['form-horizontal-align'] );
		}

		if ( isset( $this->options['use-text-logo'] ) && $this->options['use-text-logo'] ) {
			$classes[] = 'clc-text-logo';
		}

		return $classes;
	}

	public function logo_url( $url ) {
		if ( '' != $this->options['logo-url'] ) {
			return esc_url( $this->options['logo-url'] );
		}

		return $url;
	}

	public function logo_title( $title ) {
		if ( isset( $this->options['logo-title'] ) ) {
			return wp_kses_post( $this->options['logo-title'] );
		}

		return $title;
	}

	/**
	 * Output the inline CSS
	 */
	public function generate_css() {
		$instance    = Colorlib_Login_Customizer::instance();
		$css         = $this->create_css();
		$custom_css  = $this->options['custom-css'];
		$columns_css = '';

		if ( 2 == $this->options['columns'] ) {
			$widths = $this->options['columns-width'];

			$left_width = ( 100 / 12 )*absint( $widths['left'] );
			$right_width = ( 100 / 12 )*absint( $widths['right'] );

			$columns_css .= '.ml-half-screen.ml-login-align-3 .ml-container .ml-extra-div,.ml-half-screen.ml-login-align-1 .ml-container .ml-form-container{ width:' . $left_width . '%; }';
			$columns_css .= '.ml-half-screen.ml-login-align-4 .ml-container .ml-extra-div,.ml-half-screen.ml-login-align-2 .ml-container .ml-form-container{ flex-basis:' . $left_width . '%; }';

			$columns_css .= '.ml-half-screen.ml-login-align-3 .ml-container .ml-form-container,.ml-half-screen.ml-login-align-1 .ml-container .ml-extra-div{ width:' . $right_width . '%; }';
			$columns_css .= '.ml-half-screen.ml-login-align-4 .ml-container .ml-form-container,.ml-half-screen.ml-login-align-2 .ml-container .ml-extra-div{ flex-basis:' . $right_width . '%; }';

		}

		echo '<style type="text/css">#registerform #wp-submit{float:none;margin-top:15px;}.login.clc-text-logo h1 a{ background-image: none !important;text-indent: unset;width:auto !important;height: auto !important; }#login form p label br{display:none}body:not( .ml-half-screen ) .ml-form-container{background:transparent !important;}.login h1 a{background-position: center;background-size:contain !important;}.ml-container #login{ position:relative;padding: 0;width:100%;max-width:320px;margin:0;}#loginform,#registerform,#lostpasswordform{box-sizing: border-box;max-height: 100%;background-position: center;background-repeat: no-repeat;background-size: cover;}.ml-container{position:relative;min-height:100vh;display:flex;height:100%;min-width:100%;}.ml-container .ml-extra-div{background-position:center;background-size:cover;background-repeat:no-repeat}body .ml-form-container{display:flex;align-items:center;justify-content:center}body:not( .ml-half-screen ) .ml-container .ml-extra-div{position:absolute;top:0;left:0;width:100%;height:100%}body:not( .ml-half-screen ) .ml-container .ml-form-container{width:100%;min-height:100vh}body.ml-half-screen .ml-container{flex-wrap:wrap}body.ml-half-screen .ml-container>.ml-extra-div,body.ml-half-screen .ml-container>.ml-form-container{width:50%}body.ml-half-screen.ml-login-align-2 .ml-container>div,body.ml-half-screen.ml-login-align-4 .ml-container>div{width:100%;flex-basis:50%;}body.ml-half-screen.ml-login-align-2 .ml-container{flex-direction:column-reverse}body.ml-half-screen.ml-login-align-4 .ml-container{flex-direction:column}body.ml-half-screen.ml-login-align-1 .ml-container{flex-direction:row-reverse}body.ml-login-vertical-align-1 .ml-form-container{align-items:flex-start}body.ml-login-vertical-align-3 .ml-form-container{align-items:flex-end}body.ml-login-horizontal-align-1 .ml-form-container{justify-content:flex-start}body.ml-login-horizontal-align-3 .ml-form-container{justify-content:flex-end}@media only screen and (max-width: 768px) {body.ml-half-screen .ml-container > .ml-extra-div, body.ml-half-screen .ml-container > .ml-form-container{width:100%;}}.login input[type=text]:focus, .login input[type=search]:focus, .login input[type=radio]:focus, .login input[type=tel]:focus, .login input[type=time]:focus, .login input[type=url]:focus, .login input[type=week]:focus, .login input[type=password]:focus, .login input[type=checkbox]:focus, .login input[type=color]:focus, .login input[type=date]:focus, .login input[type=datetime]:focus, .login input[type=datetime-local]:focus, .login input[type=email]:focus, .login input[type=month]:focus, .login input[type=number]:focus, .login select:focus, .login textarea:focus{ box-shadow: none; }@media only screen and (max-width: 577px){body.ml-half-screen.ml-login-align-1 .ml-container .ml-extra-div, body.ml-half-screen.ml-login-align-1 .ml-container .ml-form-container,body.ml-half-screen.ml-login-align-3 .ml-container .ml-extra-div,body.ml-half-screen.ml-login-align-3 .ml-container .ml-form-container{ width: 100%; }body.ml-half-screen.ml-login-align-1 .ml-container .ml-extra-div,body.ml-half-screen.ml-login-align-3 .ml-container .ml-extra-div{position: absolute;top: 0;left: 0;width: 100%;height: 100%;}}</style>';
		echo '<style type="text/css" id="clc-style">' . $css . '</style>';
		echo '<style type="text/css" id="clc-columns-style">' . $columns_css . '</style>';
		echo '<style type="text/css" id="clc-custom-css">' . $custom_css . '</style>';
	}

	// Check general texts
	public function check_general_texts(){

		add_filter( 'gettext', array( $this, 'change_lost_password_text' ), 99, 3 );
		add_filter( 'gettext_with_context', array( $this, 'change_back_to_text' ), 99, 4 );

	}

	// Check Login page texts
	public function check_login_texts(){

		add_filter( 'gettext', array( $this, 'change_username_label' ), 99, 3 );
		add_filter( 'gettext', array( $this, 'change_password_label' ), 99, 3 );
		add_filter( 'gettext', array( $this, 'change_rememberme_label' ), 99, 3 );
		add_filter( 'gettext', array( $this, 'change_login_label' ), 99, 3 );
		add_filter( 'gettext', array( $this, 'change_register_login_link_text' ), 99, 3 );

	}

    // Check Register page texts
    public function check_register_texts(){

    	add_filter( 'gettext', array( $this, 'change_register_username_label' ), 99, 3 );
        add_filter( 'gettext', array( $this, 'change_register_email_label' ), 99, 3 );
        add_filter( 'gettext', array( $this, 'change_register_register_label' ),99,3);
        add_filter( 'gettext', array( $this, 'change_register_confirmation_text' ), 99, 3 );
        add_filter( 'gettext', array( $this, 'change_login_register_link_text' ), 99, 3 );

	}

	// Check Lost Password page texts
    public function check_lostpasswords_texts(){

    	add_filter( 'gettext', array( $this, 'change_lostpasswords_username_label' ), 99, 3 );
        add_filter( 'gettext', array( $this, 'change_lostpasswords_button_label' ), 99, 3 );

        add_filter( 'gettext', array( $this, 'change_register_login_link_text' ), 99, 3 );
        add_filter( 'gettext', array( $this, 'change_login_register_link_text' ), 99, 3 );

	}


	/**
	 * Customizer output for custom username label.
	 *
	 * @param string|string $translated_text The translated text.
	 * @param string|string $text The label we want to replace.
	 * @param string|string $domain The text domain of the site.
	 * @return string
	 */
	public function change_username_label( $translated_text, $text, $domain ) {
		$default = 'Username or Email Address';
		$label   = $this->options['username-label'];

		// Check if is our text
		if ( $default !== $text ) {
			return $translated_text;
		}

		// Check if the label is changed
		if ( $label === $text ) {
			return $translated_text;
		}else{
			$translated_text = esc_html( $label );
		}

		return $translated_text;
	}
	/**
	 * Customizer output for custom password label.
	 *
	 * @param string|string $translated_text The translated text.
	 * @param string|string $text The label we want to replace.
	 * @param string|string $domain The text domain of the site.
	 * @return string
	 */
	public function change_password_label( $translated_text, $text, $domain ) {
		$default = 'Password';
		$label   = $this->options['password-label'];

		// Check if is our text
		if ( $default !== $text ) {
			return $translated_text;
		}

		// Check if the label is changed
		if ( $label === $text ) {
			return $translated_text;
		}else{
			$translated_text = esc_html( $label );
		}

		return $translated_text;
	}

	/**
	 * Customizer output for custom remember me text.
	 *
	 * @param string|string $translated_text The translated text.
	 * @param string|string $text The label we want to replace.
	 * @param string|string $domain The text domain of the site.
	 * @return string
	 */
	public function change_rememberme_label( $translated_text, $text, $domain ) {
		$default = 'Remember Me';
		$label   = $this->options['rememberme-label'];

		// Check if is our text
		if ( $default !== $text ) {
			return $translated_text;
		}

		// Check if the label is changed
		if ( $label === $text ) {
			return $translated_text;
		} else {
			$translated_text = esc_html( $label );
		}

		return $translated_text;
	}

	/**
	 * Customizer output for custom lost your password text.
	 *
	 * @param string|string $translated_text The translated text.
	 * @param string|string $text The label we want to replace.
	 * @param string|string $domain The text domain of the site.
	 * @return string
	 */
	public function change_lost_password_text( $translated_text, $text, $domain ) {
		$default = 'Lost your password?';
		$label   = $this->options['lost-password-text'];

		// Check if is our text
		if ( $default !== $text ) {
			return $translated_text;
		}

		// Check if the label is changed
		if ( $label === $text ) {
			return $translated_text;
		}else{
			$translated_text = esc_html( $label );
		}

		return $translated_text;
	}

	/**
	 * Customizer output for custom back to text.
	 *
	 * @param string|string $translated_text The translated text.
	 * @param string|string $text The label we want to replace.
	 * @param string|string $domain The text domain of the site.
	 * @return string
	 */
	public function change_back_to_text( $translated_text, $text, $context, $domain ) {
		$default = '&larr; Back to %s';
		$label   = $this->options['back-to-text'];

		// Check if is our text
		if ( $default !== $text ) {
			return $translated_text;
		}

		// Check if the label is changed
		if ( $label === $text ) {
			return $translated_text;
		}else{
			$translated_text = '&larr; ' . esc_html( $label ) . ' %s';
		}

		return $translated_text;
	}

	/**
	 * Customizer output for custom login text.
	 *
	 * @param string|string $translated_text The translated text.
	 * @param string|string $text The label we want to replace.
	 * @param string|string $domain The text domain of the site.
	 * @return string
	 */
	public function change_login_label( $translated_text, $text, $domain ) {
		$default = 'Log In';
		$label   = $this->options['login-label'];

		// Check if is our text
		if ( $default !== $text ) {
			return $translated_text;
		}

		// Check if the label is changed
		if ( $label === $text ) {
			return $translated_text;
		} else {
			$translated_text = esc_attr( $label );
		}

		return $translated_text;
	}

    /**
     * Customizer output for custom register username label.
     *
     * @param string|string $translated_text The translated text.
     * @param string|string $text The label we want to replace.
     * @param string|string $domain The text domain of the site.
     * @return string
     */
    public function change_register_username_label( $translated_text, $text, $domain ) {
        $default = 'Username';
        $label   = $this->options['register-username-label'];

        // Check if is our text
        if ( $default !== $text ) {
            return $translated_text;
        }

        // Check if the label is changed
        if ( $label === $text ) {
            return $translated_text;
        }else{
            $translated_text = esc_html( $label );
        }

        return $translated_text;
    }

    /**
     * Customizer output for custom register email label.
     *
     * @param string|string $translated_text The translated text.
     * @param string|string $text The label we want to replace.
     * @param string|string $domain The text domain of the site.
     * @return string
     */
    public function change_register_email_label( $translated_text, $text, $domain ) {
        $default = 'Email';
        $label   = $this->options['register-email-label'];

        // Check if is our text
        if ( $default !== $text ) {
            return $translated_text;
        }

        // Check if the label is changed
        if ( $label === $text ) {
            return $translated_text;
        }else{
            $translated_text = esc_html( $label );
        }

        return $translated_text;
    }

    /**
     * Customizer output for custom registration confirmation text.
     *
     * @param string|string $translated_text The translated text.
     * @param string|string $text The label we want to replace.
     * @param string|string $domain The text domain of the site.
     * @return string
     */
    public function change_register_confirmation_text( $translated_text, $text, $domain ) {
        $default = 'Registration confirmation will be emailed to you.';
        $label   = $this->options['register-confirmation-email'];
        // Check if is our text
        if ( $default !== $text ) {
            return $translated_text;
        }

        // Check if the label is changed
        if ( $label === $text ) {
            return $translated_text;
        }else{
            $translated_text = esc_html( $label );
        }


        return $translated_text;
    }

    /**
     * Customizer output for custom register button text.
     *
     * @param string|string $translated_text The translated text.
     * @param string|string $text The label we want to replace.
     * @param string|string $domain The text domain of the site.
     * @return string
     */
    public function change_register_register_label( $translated_text, $text, $domain ) {
        $default = 'Register';
        $label   = $this->options['register-button-label'];

        // Check if is our text
        if ( $default !== $text ) {
            return $translated_text;
        }

        // Check if the label is changed
        if ( $label === $text ) {
            return $translated_text;
        }else{
            $translated_text = esc_html( $label );
        }

        return $translated_text;
    }

    public function change_login_register_link_text( $translated_text, $text, $domain ) {
        $default = 'Log in';
        $label   = $this->options['login-link-label'];

        // Check if is our text
        if ( $default !== $text ) {
            return $translated_text;
        }

        // Check if the label is changed
        if ( $label === $text ) {
            return $translated_text;
        }else{
            $translated_text = esc_html( $label );
        }

        return $translated_text;
    }

    public function change_register_login_link_text( $translated_text, $text, $domain ) {
        $default = 'Register';
        $label   = $this->options['register-link-label'];

        // Check if is our text
        if ( $default !== $text ) {
            return $translated_text;
        }

        // Check if the label is changed
        if ( $label === $text ) {
            return $translated_text;
        }else{
            $translated_text = esc_html( $label );
        }

        return $translated_text;
    }

    public function change_lostpasswords_username_label( $translated_text, $text, $domain ) {
        $default = 'Username or Email Address';
        $label   = $this->options['lostpassword-username-label'];

        // Check if is our text
        if ( $default !== $text ) {
            return $translated_text;
        }

        // Check if the label is changed
        if ( $label === $text ) {
            return $translated_text;
        }else{
            $translated_text = esc_html( $label );
        }

        return $translated_text;
    }

    public function change_lostpasswords_button_label( $translated_text, $text, $domain ) {
        $default = 'Get New Password';
        $label   = $this->options['lostpassword-button-label'];

        // Check if is our text
        if ( $default !== $text ) {
            return $translated_text;
        }

        // Check if the label is changed
        if ( $label === $text ) {
            return $translated_text;
        }else{
            $translated_text = esc_html( $label );
        }

        return $translated_text;
    }
}
