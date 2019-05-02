<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
};

/**
 * Class Colorlib_Login_Customizer_Customizer
 */
class Colorlib_Login_Customizer_Customizer {
	/**
	 * @var
	 */
	public $settings;
	/**
	 * The main plugin object.
	 *
	 * @var    object
	 * @access   public
	 * @since    1.0.0
	 */
	public $parent = null;

	/**
	 * Colorlib_Login_Customizer_Customizer constructor.
	 *
	 * @param $parent
	 * @param $manager
	 */
	public function __construct( $parent, $manager ) {
		//Plugin object
		$this->parent = $parent;
		// Initialise settings
		$this->init_settings();
		// Register plugin sections and settings
		$this->register_settings( $manager );
		// Load customizer assets
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_enqueue_scripts' ), 25 );
		add_action( 'customize_preview_init', array( $this, 'customize_preview_styles' ), 25 );
	}

	/**
	 * Initialise settings
	 *
	 * @return void
	 */
	public function init_settings() {
		$this->settings = $this->settings_fields();
	}

	private function generate_name( $id ) {
		return $this->parent->key_name . '[' . $id . ']';
	}

	/**
	 * Build settings fields
	 *
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields() {
		$settings['templates'] = array(
			'title'       => esc_html__( 'Templates', 'colorlib-login-customizer' ),
			'description' => '',
			'fields'      => array(
				array(
					'id'          => 'templates',
					'label'       => esc_html__( 'Temapltes', 'colorlib-login-customizer' ),
					'description' => '',
					'type'        => 'clc-templates',
					'default'     => 'default',
					'choices'     => array(
						'default'     => array(
							'url'     => esc_url( $this->parent->assets_url ) . 'img/default.jpg',
							'options' => array(
								/**
								 * Layout
								 */
								'columns'                  => '1',
								'columns-width'            => array(
									'left'  => 6,
									'right' => 6,
								),
								'form-column-align'        => '3',
								'form-vertical-align'      => '2',
								/**
								 * Logo section
								 */
								'hide-logo'                => 0,
								'use-text-logo'            => 0,
								'logo-url'                 => site_url(),
								'custom-logo'              => '',
								'logo-text-color'          => '#444',
								'logo-text-size'           => '20',
								'logo-text-color-hover'    => '#00a0d2',
								'logo-width'               => '84',
								'logo-height'              => '84',
								/**
								 * Background section
								 */
								'custom-background'        => '',
								'custom-background-form'   => '',
								'custom-background-color'  => '#f1f1f1',
								'custom-background-color-form' => '#f1f1f1',
								/**
								 * Form section
								 */
								'form-width'               => '320',
								'form-height'              => '297',
								'form-background-image'    => '',
								'form-background-color'    => '#ffffff',
								'form-padding'             => '26px 24px',
								'form-border'              => '0 none',
								'form-shadow'              => '0 1px 3px rgba(0,0,0,.13)',
								'form-border-radius'       => 'unset',
								'form-field-border-radius' => 'unset',
								'form-field-border'        => '1px solid #ddd',
								'form-field-width'         => '',
								'form-field-margin'        => '2px 6px 16px 0',
								'form-field-background'    => '#fbfbfb',
								'form-field-color'         => '#32373c',
								'form-label-color'         => '#72777c',
								'hide-extra-links'         => 0,
								'username-label'           => 'Username or Email Address',
								'password-label'           => 'Password',
								/**
								 * Others section ( misc )
								 */
								'button-background'        => '#0085ba',
								'button-background-hover'  => '#008ec2',
								'button-border-color'      => '#0073aa',
								'button-border-color-hover' => '#006799',
								'button-shadow'            => '0 1px 0 #006799',
								'button-text-shadow'       => '0 -1px 1px #006799, 1px 0 1px #006799, 0 1px 1px #006799, -1px 0 1px #006799',
								'button-color'             => '#ffffff',
								'link-color'               => '#555d66',
								'link-color-hover'         => '#00a0d2',
								'hide-rememberme'          => false,
								'custom-css'               => '',
							),
						),
						'template-01' => array(
							'url'     => esc_url( $this->parent->assets_url ) . 'img/template-01.jpg',
							'options' => array(
								'columns'               => '2',
								'form-column-align'     => '1',
								'form-background-color' => 'rgba(255, 255, 255, 1)',
								'custom-background'     => esc_url( $this->parent->assets_url ) . 'img/background.jpg',
								'custom-css'            => '.ml-form-container .submit input[type=\'submit\']{box-shadow:none;}.ml-form-container input[type=\'text\'],.ml-form-container input[type=\'password\']{box-shadow:none;}  ',
								'lost-password-text'    => 'Lost your password?',
								'back-to-text'          => 'Back to',
							),
						),
						'template-02' => array(
							'url'     => esc_url( $this->parent->assets_url ) . 'img/template-02.jpg',
							'options' => array(
								'hide-logo'             => '1',
								'hide-extra-links'      => '1',
								'custom-background'     => esc_url( $this->parent->assets_url ) . 'img/background-1.jpg',
								'form-background-color' => 'rgba(255, 255, 255, 0)',
								'form-shadow'           => 'none',
								'custom-css'            => '.ml-form-container input[type=\'password\'],.ml-form-container input[type=\'text\']{box-shadow:none;}.ml-form-container .submit input[type=\'submit\']{box-shadow:none;}',
							),
						),
						'template-03' => array(
							'url'     => esc_url( $this->parent->assets_url ) . 'img/tpl-03/screen.jpg',
							'options' => array(
								'columns'                 => '2',
								'columns-width'           => array(
									'left'  => 8,
									'right' => 4,
								),
								/**
								 * Form section
								 */
								'form-width'              => '430',
								'form-height'             => '350',
								'form-column-align'       => '3',
								'hide-logo'               => '1',
								'hide-extra-links'        => '1',
								'custom-background-color' => '#f7f7f7',
								'custom-background'       => esc_url( $this->parent->assets_url ) . 'img/tpl-03/bg.jpg',
								'form-background-color'   => 'rgba(255,255,255,0)',
								'form-shadow'             => 'none',
								'custom-css'              => ".ml-form-container .submit input[type='submit'] {width: 100%; box-sizing: border-box;display: inline-block;text-align: center;border-radius: 30px;padding: 0 20px;height: 38px;line-height: 1;font-weight: bold;vertical-align: middle;box-shadow:none; }.ml-form-container input[type='password'],.ml-form-container input[type='text']{box-shadow:none;}",
								'form-field-background'   => '#f7f7f7',
								'button-background'       => '#6675df',
								'button-background-hover' => '#333333',
								'button-border-color'     => '#6675df',
								'button-border-color-hover' => '#333333',
								'hide-rememberme'         => '1',
								/**
								 * Logo section
								 */
								'hide-logo'               => 0,
								'use-text-logo'           => 1,
								'logo-title'               => 'Login to continue',
								'logo-url'                => site_url(),
								'custom-logo'             => '',
								'logo-text-color'         => '#333',
								'logo-text-size'          => '30',
								'logo-text-color-hover'   => '#00a0d2',
								'logo-width'              => '350',
								'logo-height'             => '0',
								'username-label'          => 'Username',
								'password-label'          => 'Password',
							),
						),
						'template-04' => array(
							'url'     => esc_url( $this->parent->assets_url ) . 'img/tpl-04/screen.jpg',
							'options' => array(
								'form-width'               => '350',
								'form-height'              => '450',
								'form-padding'             => '50px 30px',
								'columns'                  => '1',
								'hide-logo'                => '1',
								'hide-extra-links'         => 0,
								'custom-background-color'  => '#e9faff',
								'custom-background'        => '',
								'form-background-color'    => 'rgba(255,255,255,1)',
								'form-shadow'              => 'none',
								'form-field-background'    => '#FFF',
								'button-background'        => '#4272d7',
								'button-background-hover'  => '#333333',
								'button-border-color'      => '#4272d7',
								'button-border-color-hover' => '#333333',
								'hide-rememberme'          => 1,
								/**
								 * Logo section
								 */
								'hide-logo'                => 0,
								'use-text-logo'            => 1,
								'logo-title'                => 'Account Login',
								'logo-url'                 => site_url(),
								'custom-logo'              => '',
								'logo-text-color'          => '#333',
								'logo-text-size'           => '20',
								'logo-text-color-hover'    => '#00a0d2',
								'logo-width'               => '350',
								'logo-height'              => '0',
								'username-label'           => '',
								'password-label'           => '',
								'form-shadow'              => '0 3px 20px 0px rgba(0, 0, 0, 0.1)',
								'custom-css'               => '',
								'form-field-border-radius' => '0',
								'form-field-margin'        => '0',
								'form-field-border'        => '1px solid #eee',
								'custom-css'               => ".ml-form-container .submit input[type='submit'] {width: 100%; box-sizing: border-box;display: inline-block;text-align: center;padding: 0 20px;height: 38px;line-height: 1;font-weight: bold;vertical-align: middle; margin-top: 15px;box-shadow:none;} .ml-form-container input[type='text'], .ml-form-container input[type='password'] { height: 50px; } .login .ml-form-container #backtoblog,.login .ml-form-container #nav{position:absolute;left:0;right:0;margin-right:auto!important;bottom:50px;max-width:100%;text-align:center}.login .ml-form-container #nav{bottom:40px}.ml-form-container #login>h1{position:absolute;top:40px}.login-action-register .ml-form-container #login>h1{top:65px;}.ml-form-container input[type='text'],.ml-form-container input[type='password']{box-shadow:none;}",
							),
						),
					),
				),
			),
		);

		$settings['logo'] = array(
			'title'       => esc_html__( 'Logo options', 'colorlib-login-customizer' ),
			'description' => '',
			'fields'      => array(
				array(
					'id'          => 'hide-logo',
					'label'       => esc_html__( 'Hide Logo', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'Show/Hide the logo from login page', 'colorlib-login-customizer' ),
					'type'        => 'clc-toggle',
					'default'     => 0,
				),
				array(
					'id'          => 'use-text-logo',
					'label'       => esc_html__( 'Use Text Logo', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'Show/Hide the logo text from login page', 'colorlib-login-customizer' ),
					'type'        => 'clc-toggle',
					'default'     => 0,
				),
				array(
					'id'          => 'logo-url',
					'label'       => esc_html__( 'Logo URL', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This is where the logo will link to.', 'colorlib-login-customizer' ),
					'type'        => 'text',
					'default'     => site_url(),
				),
				array(
					'id'          => 'logo-title',
					'label'       => esc_html__( 'Logo Title', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'The tooltip that will be displayed when hovering over the logo. Also this is used as Logo text when you select "Use Text Logo"', 'colorlib-login-customizer' ),
					'type'        => 'text',
					'default'     => 'Powered by WordPress',
				),
				array(
					'id'              => 'logo-text-color',
					'label'           => esc_html__( 'Logo text color', 'colorlib-login-customizer' ),
					'description'     => esc_html__( 'This will change the color text property.', 'colorlib-login-customizer' ),
					'type'            => 'color',
					'default'         => '#444',
					'active_callback' => array( $this, 'check_if_text_logo' ),
				),
				array(
					'id'              => 'logo-text-color-hover',
					'label'           => esc_html__( 'Logo text color hover', 'colorlib-login-customizer' ),
					'description'     => esc_html__( 'This will change the color text property on hover.', 'colorlib-login-customizer' ),
					'type'            => 'color',
					'default'         => '#00a0d2',
					'active_callback' => array( $this, 'check_if_text_logo' ),
				),
				array(
					'id'              => 'logo-text-size',
					'label'           => esc_html__( 'Logo text size', 'colorlib-login-customizer' ),
					'description'     => esc_html__( 'This will change the text size of logo.', 'colorlib-login-customizer' ),
					'default'         => 20,
					'choices'         => array(
						'min'  => 10,
						'max'  => 120,
						'step' => 1,
					),
					'type'            => 'clc-range-slider',
					'active_callback' => array( $this, 'check_if_text_logo' ),
				),
				array(
					'id'              => 'custom-logo',
					'label'           => esc_html__( 'Custom logo', 'colorlib-login-customizer' ),
					'description'     => esc_html__( 'This will upload an image to your media library and store the attachment ID in the option field. Once you have uploaded an image the thumbnail will display above these buttons.', 'colorlib-login-customizer' ),
					'type'            => 'image',
					'default'         => '',
					'active_callback' => array( $this, 'check_if_not_text_logo' ),
				),
				array(
					'id'          => 'logo-width',
					'label'       => esc_html__( 'Logo Width', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'Make sure you set the logo width to match your image.', 'colorlib-login-customizer' ),
					'default'     => 84,
					'choices'     => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'type'        => 'clc-range-slider',
					'active_callback' => array( $this, 'check_if_not_text_logo' ),
				),
				array(
					'id'          => 'logo-height',
					'label'       => esc_html__( 'Logo Height', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'Make sure you set the logo height to match your image.', 'colorlib-login-customizer' ),
					'default'     => 20,
					'choices'     => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'type'        => 'clc-range-slider',
					'active_callback' => array( $this, 'check_if_not_text_logo' ),
				),
			),
		);

		$settings['layout'] = array(
			'title'       => esc_html__( 'Layout options', 'colorlib-login-customizer' ),
			'description' => '',
			'fields'      => array(
				array(
					'id'          => 'columns',
					'label'       => esc_html__( 'Columns', 'colorlib-login-customizer' ),
					'description' => '',
					'default'     => 1,
					'choices'     => array(
						1 => array(
							'value' => 1,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/one-column.png',
						),
						2 => array(
							'value' => 2,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/two-column.png',
						),
					),
					'type'        => 'clc-button-group',
				),
				array(
					'id'              => 'columns-width',
					'label'           => esc_html__( 'Columns Width', 'colorlib-login-customizer' ),
					'description'     => '',
					'type'            => 'clc-column-width',
					'active_callback' => array( $this, 'check_two_column_layout' ),
				),
				array(
					'id'              => 'form-column-align',
					'label'           => esc_html__( 'Form Column Alignment', 'colorlib-login-customizer' ),
					'description'     => '',
					'default'         => 3,
					'choices'         => array(
						'left'   => array(
							'value' => 1,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/form-align-left.jpg',
						),
						'top'    => array(
							'value' => 2,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/form-align-top.jpg',
						),
						'right'  => array(
							'value' => 3,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/form-align-right.jpg',
						),
						'bottom' => array(
							'value' => 4,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/form-align-bottom.jpg',
						),
					),
					'type'            => 'clc-button-group',
					'active_callback' => array( $this, 'check_two_column_layout' ),
				),
				array(
					'id'          => 'form-vertical-align',
					'label'       => esc_html__( 'Form Vertical Alignment', 'colorlib-login-customizer' ),
					'description' => '',
					'default'     => 2,
					'choices'     => array(
						'top'    => array(
							'value' => 1,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/form-vertical-align-top.png',
						),
						'middle' => array(
							'value' => 2,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/form-vertical-align-middle.png',
						),
						'bottom' => array(
							'value' => 3,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/form-vertical-align-bottom.png',
						),
					),
					'type'        => 'clc-button-group',
				),
				array(
					'id'          => 'form-horizontal-align',
					'label'       => esc_html__( 'Form Horizontal Alignment', 'colorlib-login-customizer' ),
					'description' => '',
					'default'     => 2,
					'choices'     => array(
						'left'   => array(
							'value' => 1,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/form-horizontal-align-left.png',
						),
						'middle' => array(
							'value' => 2,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/form-vertical-align-middle.png',
						),
						'right'  => array(
							'value' => 3,
							'png'   => COLORLIB_LOGIN_CUSTOMIZER_URL . '/assets/img/form-horizontal-align-right.png',
						),
					),
					'type'        => 'clc-button-group',
				),
			),
		);

		$settings['background'] = array(
			'title'       => esc_html__( 'Background options', 'colorlib-login-customizer' ),
			'description' => '',
			'fields'      => array(
				array(
					'id'          => 'custom-background-color',
					'label'       => esc_html__( 'Custom background color', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This will change the background color property.', 'colorlib-login-customizer' ),
					'type'        => 'color',
					'default'     => '#f1f1f1',
				),
				array(
					'id'          => 'custom-background',
					'label'       => esc_html__( 'Custom background', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This will upload an image to your media library and store the attachment ID in the option field. Once you have uploaded an image the thumbnail will display above these buttons.', 'colorlib-login-customizer' ),
					'type'        => 'image',
					'default'     => '',
				),
				array(
					'id'              => 'custom-background-color-form',
					'label'           => esc_html__( 'Form Column background color', 'colorlib-login-customizer' ),
					'description'     => esc_html__( 'This will change the background color property.', 'colorlib-login-customizer' ),
					'type'            => 'color',
					'default'         => '#f1f1f1',
					'active_callback' => array( $this, 'check_two_column_layout' ),
				),
				array(
					'id'              => 'custom-background-form',
					'label'           => esc_html__( 'Form Column background', 'colorlib-login-customizer' ),
					'description'     => esc_html__( 'This will upload an image to your media library and store the attachment ID in the option field. Once you have uploaded an image the thumbnail will display above these buttons.', 'colorlib-login-customizer' ),
					'type'            => 'image',
					'default'         => '',
					'active_callback' => array( $this, 'check_two_column_layout' ),
				),
			),
		);

		$settings['form'] = array(
			'title'       => esc_html__( 'General Form options', 'colorlib-login-customizer' ),
			'description' => '',
			'fields'      => array(
				array(
                    'id'          => 'form-width',
                    'label'       => esc_html__( 'Form Width', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'Please input the desired width for the login form in pixels. Example: 20', 'colorlib-login-customizer' ),
                    'default'     => 320,
                    'type'        => 'clc-range-slider',
                    'choices'     => array(
                        'min'  => 150,
                        'max'  => 1000,
                        'step' => 5,
                    ),
                ),
                array(
                    'id'          => 'form-height',
                    'label'       => esc_html__( 'Form Height', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'Please input the desired height for the login form in pixels. Example: 20', 'colorlib-login-customizer' ),
                    'default'     => 194,
                    'choices'     => array(
                        'min'  => 150,
                        'max'  => 1000,
                        'step' => 5,
                    ),
                    'type'        => 'clc-range-slider',
                ),
                array(
                    'id'          => 'form-background-image',
                    'label'       => esc_html__( 'Form background image', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'This will change the background image property of login form.', 'colorlib-login-customizer' ),
                    'type'        => 'image',
                    'default'     => '',
                ),
                array(
                    'id'          => 'form-background-color',
                    'label'       => esc_html__( 'Form background color', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'This will change the background color property.', 'colorlib-login-customizer' ),
                    'type'        => 'color',
                    'default'     => '#ffffff',
                ),
                array(
                    'id'          => 'form-padding',
                    'label'       => esc_html__( 'Form padding', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'This will change the padding property. Example: 26px 24px 46px 30px', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => '26px 24px',
                ),
                array(
                    'id'          => 'form-border',
                    'label'       => esc_html__( 'Form border', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'This will change the border property. Example: 2px dotted black', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => '0 none',
                ),
                array(
                    'id'          => 'form-border-radius',
                    'label'       => esc_html__( 'Form border radius', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'This will change the border radius property. Example: 2px 2px 2px 2px', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => '0',
                ),
                array(
                    'id'          => 'form-shadow',
                    'label'       => esc_html__( 'Form shadow', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'This will change the form\'s shadow property. Example: 0 1px 0 #006799', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => '0 1px 3px rgba(0,0,0,.13)',
                ),
                array(
                    'id'          => 'form-field-width',
                    'label'       => esc_html__( 'Form field width', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'Please input the desired width for the form field in pixels. Example: 20', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => '100%',
                ),
                array(
                    'id'          => 'form-field-margin',
                    'label'       => esc_html__( 'Form field margin', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'This will change the margin property. Example: 26px 24px 46px 30px', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => '2px 6px 16px 0',
                ),
                array(
                    'id'          => 'form-field-border',
                    'label'       => esc_html__( 'Form field border', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'Please input the desired border for the form field. Example: 2px dotted black', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => '1px solid #ddd',
                ),
                array(
                    'id'          => 'form-field-border-radius',
                    'label'       => esc_html__( 'Form field border radius', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'Please input the desired border radiuse for the form field. Example: 5px 5px 5px 5px', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'unset',
                ),
                array(
                    'id'          => 'form-field-background',
                    'label'       => esc_html__( 'Form field background', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'This will change the background color property.', 'colorlib-login-customizer' ),
                    'type'        => 'color',
                    'default'     => '#fbfbfb',
                ),
                array(
                    'id'          => 'form-field-color',
                    'label'       => esc_html__( 'Form field color', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'This will change the text color property.', 'colorlib-login-customizer' ),
                    'type'        => 'color',
                    'default'     => '#32373c',
                ),
                array(
                    'id'          => 'form-label-color',
                    'label'       => esc_html__( 'Form label color', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'This will change the label text color property.', 'colorlib-login-customizer' ),
                    'type'        => 'color',
                    'default'     => '#72777c',
                ),
                array(
                    'id'          => 'lost-password-text',
                    'label'       => esc_html__( 'Lost Password Text', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for "Lost your password" ', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Lost your password?',
                ),
                array(
                    'id'          => 'back-to-text',
                    'label'       => esc_html__( 'Back to site text', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for "Back to" site ', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Back to site',
                ),
                array(
                    'id'          => 'hide-extra-links',
                    'label'       => esc_html__( 'Hide Extra Links', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'Show/Hide the links under the login form', 'colorlib-login-customizer' ),
                    'type'        => 'clc-toggle',
                    'default'     => 0,
                ),
			),
		);

		$settings['login-form'] = array(
			'title'       => esc_html__( 'Login Form Texts', 'colorlib-login-customizer' ),
            'description' => '',
            'fields'      => array(
            	array(
                    'id'          => 'username-label',
                    'label'       => esc_html__( 'Username label', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for username label or just delete it.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Username or Email Address',
                ),
                array(
                    'id'          => 'password-label',
                    'label'       => esc_html__( 'Password label', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for password label or just delete it.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Password',
                ),
                array(
                    'id'          => 'rememberme-label',
                    'label'       => esc_html__( 'Remember Me label', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default remember me text.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Remember Me',
                ),
                array(
                    'id'          => 'login-label',
                    'label'       => esc_html__( 'Login label', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for the log in button.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Log In',
                ),
                array(
                    'id'          => 'register-link-label',
                    'label'       => esc_html__( 'Register link', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for the register link at the end of the form.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Register',
                    'active_callback' => array( $this, 'check_if_user_can_register' ),
                ),
            ),
        );

        $settings['register-form'] = array(
            'title'       => esc_html__( 'Register Form Texts', 'colorlib-login-customizer' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'          => 'register-username-label',
                    'label'       => esc_html__( 'Username label', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for username label or just delete it.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Username',
                    'active_callback' => array( $this, 'check_if_user_can_register' ),
                ),
                array(
                    'id'          => 'register-email-label',
                    'label'       => esc_html__( 'Email label', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for email label or just delete it.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Email',
                    'active_callback' => array( $this, 'check_if_user_can_register' ),
                ),
                array(
                    'id'          => 'register-confirmation-email',
                    'label'       => esc_html__( 'Registration confirmation text', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default registration confirmation text.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Registration confirmation will be emailed to you.',
                    'active_callback' => array( $this, 'check_if_user_can_register' ),
                ),
                array(
                    'id'          => 'register-button-label',
                    'label'       => esc_html__( 'Button label', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for the register button.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Register',
                    'active_callback' => array( $this, 'check_if_user_can_register' ),
                ),
                array(
                    'id'          => 'login-link-label',
                    'label'       => esc_html__( 'Login link', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for the login link at the end of the form.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Log in',
                    'active_callback' => array( $this, 'check_if_user_can_register' ),
                ),
            ),
        );

        $settings['lostpassword-form'] = array(
            'title'       => esc_html__( 'Lost Password Form Texts', 'colorlib-login-customizer' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'          => 'lostpassword-username-label',
                    'label'       => esc_html__( 'Username label', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for username label or just delete it.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Username or Email Address',
                ),
                array(
                    'id'          => 'lostpassword-button-label',
                    'label'       => esc_html__( 'Button label', 'colorlib-login-customizer' ),
                    'description' => esc_html__( 'You can change the default text for the lost password button.', 'colorlib-login-customizer' ),
                    'type'        => 'text',
                    'default'     => 'Get New Password',
                ),
            ),

        );

		$settings['general'] = array(
			'title'       => esc_html__( 'Form Button & Links', 'colorlib-login-customizer' ),
			'description' => '',
			'fields'      => array(
				array(
					'id'          => 'button-background',
					'label'       => esc_html__( 'Button background', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This will change the submit button\'s background property', 'colorlib-login-customizer' ),
					'type'        => 'color',
					'default'     => '#0085ba',
				),
				array(
					'id'          => 'button-background-hover',
					'label'       => esc_html__( 'Button background hover state', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This will change the submit button\'s background property on hover', 'colorlib-login-customizer' ),
					'type'        => 'color',
					'default'     => '#008ec2',
				),
				array(
					'id'          => 'button-border-color',
					'label'       => esc_html__( 'Button border color', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This will change the submit button\'s border color property', 'colorlib-login-customizer' ),
					'type'        => 'color',
					'default'     => '#0073aa',
				),
				array(
					'id'          => 'button-border-color-hover',
					'label'       => esc_html__( 'Button border hover state', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This will change the submit button\'s border property on hover', 'colorlib-login-customizer' ),
					'type'        => 'color',
					'default'     => '#006799',
				),
				array(
					'id'          => 'button-shadow',
					'label'       => esc_html__( 'Button shadow', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This will change the submit button\'s shadow property. Example: 0 1px 0 #006799', 'colorlib-login-customizer' ),
					'type'        => 'text',
					'default'     => '0 1px 0 #006799',
				),
				array(
					'id'          => 'button-text-shadow',
					'label'       => esc_html__( 'Button text shadow', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This will change the submit button text\'s shadow property. Example: 0 -1px 1px #006799', 'colorlib-login-customizer' ),
					'type'        => 'text',
					'default'     => '0 -1px 1px #006799',
				),
				array(
					'id'          => 'button-color',
					'label'       => esc_html__( 'Button color', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This will change the submit button\'s text color property', 'colorlib-login-customizer' ),
					'type'        => 'color',
					'default'     => '#ffffff',
				),
				array(
					'id'          => 'link-color',
					'label'       => esc_html__( 'Link color', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This will change the text color of links that are underneath the login form', 'colorlib-login-customizer' ),
					'type'        => 'color',
					'default'     => '#555d66',
				),
				array(
					'id'          => 'link-color-hover',
					'label'       => esc_html__( 'Link color hover', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'This will change the text color of links, that are underneath the login form, on hover', 'colorlib-login-customizer' ),
					'type'        => 'color',
					'default'     => '#00a0d2',
				),
				array(
					'id'          => 'hide-rememberme',
					'label'       => esc_html__( 'Hide "Remember Me"', 'colorlib-login-customizer' ),
					'description' => esc_html__( 'Show/Hide the "Remember Me" checkbox', 'colorlib-login-customizer' ),
					'type'        => 'clc-toggle',
					'default'     => 0,
				),
			),
		);

		$settings['clc-custom-css'] = array(
			'title'       => esc_html__( 'Custom CSS', 'colorlib-login-customizer' ),
			'description' => '',
			'fields'      => array(
				array(
					'id'          => 'custom-css',
					'label'       => __( 'CSS code', 'colorlib-login-customizer' ),
					'description' => '',
					'code_type'   => 'text/css',
					'type'        => 'custom-css',
					'input_attrs' => array(
						'aria-describedby' => 'editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4',
					),
				),
			),
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register settings in the customizer
	 */
	public function register_settings( $manager ) {
		$manager->add_panel(
			'clc_main_panel',
			array(
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => esc_html__( 'Colorlib Login Customizer', 'colorlib-login-customizer' ),
			)
		);

		foreach ( $this->settings as $section => $properties ) {
			$manager->add_section(
				'clc_' . $section,
				array(
					'title'       => $properties['title'],
					'description' => $properties['description'],
					'panel'       => 'clc_main_panel',
				)
			);

			foreach ( $properties['fields'] as $setting ) {
				$key_name      = $this->generate_name( $setting['id'] );
				$settings_args = array(
					'type'      => 'option',
					'transport' => 'postMessage',
				);

				if ( isset( $setting['default'] ) ) {
					$settings_args['default'] = $setting['default'];
				}

				$manager->add_setting( $key_name, $settings_args );

				$control_args = array(
					'label'       => $setting['label'],
					'description' => $setting['description'],
					'section'     => 'clc_' . $section,
				);

				if ( isset( $setting['active_callback'] ) ) {
					$control_args['active_callback'] = $setting['active_callback'];
				}

				if ( isset( $setting['choices'] ) ) {
					$control_args['choices'] = $setting['choices'];
				}

				if ( isset( $setting['code_type'] ) ) {
					$control_args['code_type'] = $setting['code_type'];
				}

				if ( isset( $setting['input_attrs'] ) ) {
					$control_args['input_attrs'] = $setting['input_attrs'];
				}

				switch ( $setting['type'] ) {
					case 'image':
						$manager->add_control(
							new WP_Customize_Image_Control( $manager, $key_name, $control_args )
						);
						break;
					case 'color':
						$manager->add_control(
							new Colorlib_Login_Customizer_Control_Color_Picker( $manager, $key_name, $control_args )
						);
						break;
					case 'clc-range-slider':
						$manager->add_control(
							new Colorlib_Login_Customizer_Range_Slider_Control( $manager, $key_name, $control_args )
						);
						break;
					case 'clc-templates':
						$manager->add_control(
							new Colorlib_Login_Customizer_Template_Control( $manager, $key_name, $control_args )
						);
						break;
					case 'clc-button-group':
						$manager->add_control(
							new Colorlib_Login_Customizer_Button_Group_Control( $manager, $key_name, $control_args )
						);
						break;
					case 'clc-column-width':
						$manager->add_control(
							new Colorlib_Login_Customizer_Column_Width( $manager, $key_name, $control_args )
						);
						break;
					case 'clc-toggle':
						$manager->add_control(
							new Colorlib_Login_Customizer_Control_Toggle( $manager, $key_name, $control_args )
						);
						break;
					case 'custom-css':
						$manager->add_control(
							new WP_Customize_Code_Editor_Control( $manager, $key_name, $control_args )
						);
						break;
					default:
						$manager->add_control( $key_name, $control_args );
						break;
				}
			}
		}

	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	public function customize_preview_styles() {
		wp_enqueue_style( 'colorlib-login-customizer-previewer', esc_url( $this->parent->assets_url ) . 'css/clc-customizer-previewer.css' );
		wp_enqueue_script( 'colorlib-login-customizer-preview', esc_url( $this->parent->assets_url ) . 'js/clc-preview.js', array( 'customize-preview' ), false, true );
	}

	/*
	 * Our Customizer script
	 *
	 * Dependencies: Customizer Controls script (core)
	 */
	public function customizer_enqueue_scripts() {
		wp_enqueue_style( 'colorlib-login-customizer-minicolors-styles', esc_url( $this->parent->assets_url ) . 'css/jquery.minicolors.css' );
		wp_enqueue_style( 'colorlib-login-customizer-styles', esc_url( $this->parent->assets_url ) . 'css/clc-customizer.css' );
		wp_enqueue_script(
			'colorlib-login-customizer-minicolors-script', esc_url( $this->parent->assets_url ) . 'js/jquery.minicolors.js', array(
				'jquery',
			), false, true
		);
		wp_enqueue_script(
			'colorlib-login-customizer-script', esc_url( $this->parent->assets_url ) . 'js/clc-customizer.js', array(
				'jquery',
				'customize-controls',
				'colorlib-login-customizer-minicolors-script',
			), false, true
		);

		wp_localize_script(
			'colorlib-login-customizer-script', 'CLCUrls', array(
				'siteurl' => get_option( 'siteurl' ),
                'register_url' => wp_registration_url()
			)
		);
	}

	// Active callbacks
	public function check_two_column_layout( $control ) {
		$options = get_option( 'clc-options', array() );

		if ( '2' == $control->manager->get_setting( 'clc-options[columns]' )->value() ) {
			return true;
		}

		return false;
	}

	public function check_if_text_logo( $control ) {
		$options = get_option( 'clc-options', array() );

		if ( 1 == $control->manager->get_setting( 'clc-options[use-text-logo]' )->value() ) {
			return true;
		}

		return false;
	}

	public function check_if_not_text_logo( $control ) {
		$options = get_option( 'clc-options', array() );

		if ( 1 == $control->manager->get_setting( 'clc-options[use-text-logo]' )->value() ) {
			return false;
		}

		return true;
	}

	public function check_if_user_can_register(){
	    $user_can_register = get_option('users_can_register');
	    if($user_can_register == '0'){
	        return false;
        }
	     return true;
    }
}
