( function( $ ) {
    'use strict';

    if ( 'undefined' !== typeof( wp ) && 'undefined' !== typeof( wp.customize ) ) {

        // Detect when the templates section is expanded (or closed) so we can hide the templates shortcut when it's open.
        wp.customize.panel( 'clc_main_panel', function( section ) {
            section.expanded.bind( function( isExpanding ) {
                var loginURL = CLCUrls.siteurl + '?colorlib-login-customizer-customization=true';
                // Value of isExpanding will = true if you're entering the section, false if you're leaving it.
                if ( isExpanding ) {
                    wp.customize.previewer.previewUrl.set( loginURL );
                } else {
                    wp.customize.previewer.previewUrl.set( CLCUrls.siteurl );
                }
            });
        });

        wp.customize.section( 'clc_register-form', function( section ) {
            section.expanded.bind( function( isExpanding ) {
                // Value of isExpanding will = true if you're entering the section, false if you're leaving it.
                var loginURL = CLCUrls.siteurl + '?colorlib-login-customizer-customization=true&action=register';
                if ( isExpanding ) {
                    wp.customize.previewer.previewUrl.set( loginURL );
                    jQuery('#customize-preview').addClass('show-next-iframe');
                } else {
                    wp.customize.previewer.previewUrl.set( CLCUrls.siteurl + '?colorlib-login-customizer-customization=true' );
                    jQuery('#customize-preview').removeClass('show-next-iframe');
                }
            });
        });

        wp.customize.section( 'clc_lostpassword-form', function( section ) {
            section.expanded.bind( function( isExpanding ) {
                // Value of isExpanding will = true if you're entering the section, false if you're leaving it.
                var loginURL = CLCUrls.siteurl + '?colorlib-login-customizer-customization=true&action=retrievepassword';
                if ( isExpanding ) {
                    wp.customize.previewer.previewUrl.set( loginURL );
                    jQuery('#customize-preview').addClass('show-next-iframe');
                } else {
                    wp.customize.previewer.previewUrl.set( CLCUrls.siteurl + '?colorlib-login-customizer-customization=true' );
                    jQuery('#customize-preview').removeClass('show-next-iframe');
                }
            });
        });

        wp.customize.controlConstructor['clc-templates'] = wp.customize.Control.extend({
            ready: function() {
                var control = this;

                this.container.on( 'change', 'input:radio', function() {
                    var template = $( this ).val();

                    control.loadTemplate( 'default' );

                    if ( 'default' !== template ) {
                        control.loadTemplate( template );
                    }

                });
            },
            loadTemplate: function( optionName ) {
                var control = this,
                    options = control.params.options[ optionName ];

                $.each( options, function( index, option ) {
                    var currentControl = wp.customize.control( option.name );

                    if ( 'default' === optionName ) {
                        currentControl.setting( option.value );
                    } else {
                        currentControl.setting( option.value );
                    }

                });
            }
        });

        wp.customize.controlConstructor['clc-range-slider'] = wp.customize.Control.extend({
            ready: function() {
                var control = this,
                    controlField = control.container.find( 'input.clc-slider' ),
                    controlSlider = control.container.find( 'div.clc-slider' ),
                    controlSliderData = control.params.choices,
                    updating = false;

                controlSlider.slider({
                    range: 'min',
                    min: controlSliderData.min,
                    max: controlSliderData.max,
                    step: controlSliderData.step,
                    value: controlField.val(),
                    slide: function( event, ui ) {
                        controlField.val( ui.value ).keyup();
                    },
                    stop: function( event, ui ) {
                        controlField.val( ui.value );
                        updating = true;
                        control.setting.set( ui.value );
                        updating = false;
                    }
                });

                // Whenever the setting's value changes, refresh the preview.
                control.setting.bind( function( value ) {

                    // Bail if the update came from the control itself.
                    if ( updating ) {
                        return;
                    }

                    controlField.val( value );
                    controlSlider.slider( 'value', value );

                });

            }
        });

        wp.customize.controlConstructor['clc-button-group'] = wp.customize.Control.extend({
            ready: function() {
                var control = this,
                    updating = false;
                control.container.on( 'click', '.colorlib-login-customizer-control-group > a', function() {
                    var value = $( this ).attr( 'data-value' );
                    $( this ).siblings().removeClass( 'active' );
                    $( this ).addClass( 'active' );

                    updating = true;
                    control.setting.set( value );
                    updating = false;
                });

                // Whenever the setting's value changes, refresh the preview.
                control.setting.bind( function( value ) {

                    var options = control.container.find( '.colorlib-login-customizer-control-group > a' );

                    // Bail if the update came from the control itself.
                    if ( updating ) {
                        return;
                    }

                    options.removeClass( 'active' );
                    options.filter( '[data-value=' + value + ']' ).addClass( 'active' );

                });

            }
        });

        wp.customize.controlConstructor['clc-column-width'] = wp.customize.Control.extend({
            ready: function() {
                var control = this,
                    updating = false;

                control.values = control.params.value;

                control.container.on( 'click', '.clc-layouts-setup .clc-column > a', function() {
                    var currentAction = $( this ).data( 'action' );

                    updating = true;
                    control.updateColumns( currentAction );
                    updating = false;

                });

                // Whenever the setting's value changes, refresh the preview.
                control.setting.bind( function( value ) {

                    // Bail if the update came from the control itself.
                    if ( updating ) {
                        return;
                    }

                    control.values = value;
                    control.rederColumns();

                });

            },

            updateColumns: function( increment ) {
                var incrementElement,
                    decrementElement,
                    control = this;

                if ( 11 === control.values[ increment ] ) {
                    return;
                }

                if ( 'left' == increment ) {
                    incrementElement = control.container.find( '.clc-column-left' );
                    decrementElement = control.container.find( '.clc-column-right' );

                    control.values['left']  += 1;
                    control.values['right'] -= 1;

                }else{
                    incrementElement = control.container.find( '.clc-column-right' );
                    decrementElement = control.container.find( '.clc-column-left' );

                    control.values['right']  += 1;
                    control.values['left'] -= 1;

                }

                // Update control values
                control.setting( '' );
                control.setting( control.values );

                control.rederColumns();

            },

            rederColumns: function() {
                var control     = this,
                    leftColumn  = control.container.find( '.clc-column-left' ),
                    rightColumn = control.container.find( '.clc-column-right' ),
                    classes     = 'col12 col11 col10 col9 col8 col7 col6 col5 col4 col3 col2 col1';

                leftColumn.removeClass( classes ).addClass( 'col' + control.values['left'] );
                rightColumn.removeClass( classes ).addClass( 'col' + control.values['right'] );

            }



        });

        wp.customize.controlConstructor['clc-color-picker'] = wp.customize.Control.extend({
            ready: function() {
                var control = this,
                    updating = false,
                    clear = control.container.find( 'a.clc-color-picker-default' ),
                    input = $( control.container ).find( '.clc-color-picker' );

                input.minicolors({
                    format: 'hex',
                    opacity: true,
                    keywords: 'transparent, initial, inherit',
                    change: function( value, opacity ) {
                        updating = true;
                        control.setting.set( input.minicolors( 'rgbaString' ) );
                        updating = false;
                    }
                });

                if ( clear.length > 0 ) {
                    clear.on( 'click', function( e ) {
                        var defaultValue = $( this ).attr( 'data-default' );
                        e.preventDefault();

                        input.minicolors( 'value', defaultValue );
                        updating = true;
                        control.setting.set( defaultValue );
                        updating = false;
                    });
                }

                // Whenever the setting's value changes, refresh the preview.
                control.setting.bind( function( value ) {

                    // Bail if the update came from the control itself.
                    if ( updating ) {
                        return;
                    }
                    input.minicolors( 'value', value );

                });
            }
        });

        // Listen for previewer events
        wp.customize.bind( 'ready', function() {
            wp.customize.previewer.bind( 'clc-focus-section', function( sectionName ) {
                var section = wp.customize.section( sectionName );

                if ( undefined !== section ) {
                    section.focus();
                }
            });

            wp.customize( 'clc-options[columns]', function( value ) {
                value.bind( function( to ) {
                    var alignControl = wp.customize.control( 'clc-options[form-column-align]' ),
                        backgroundControl = wp.customize.control( 'clc-options[custom-background-form]' ),
                        columnsWidthControl = wp.customize.control( 'clc-options[columns-width]' ),
                        backgroundColorControl = wp.customize.control( 'clc-options[custom-background-color-form]' );

                    if ( '2' === to ) {
                        alignControl.toggle( true );
                        backgroundControl.toggle( true );
                        backgroundColorControl.toggle( true );
                        columnsWidthControl.toggle( true );
                    } else {
                        alignControl.toggle( false );
                        backgroundControl.toggle( false );
                        backgroundColorControl.toggle( false );
                        columnsWidthControl.toggle( false );
                    }
                });
			});

			// validation for the login-level setting
			wp.customize( 'clc-options[login-label]', function ( setting ) {
				setting.validate = function ( value ) {
					var code, notification;

					code = 'required';
					if ( ! value ) {
						notification = new wp.customize.Notification( code, {message: 'value is empty' } );
						setting.notifications.add( code, notification );
					} else {
						setting.notifications.remove( code );
					}

					return value;
				};
			} );

            wp.customize( 'clc-options[use-text-logo]', function( value ) {
                value.bind( function( to ) {
                    var logoTextColor      = wp.customize.control( 'clc-options[logo-text-color]' ),
                        logoTextColorHover = wp.customize.control( 'clc-options[logo-text-color-hover]' ),
                        logoTextSize       = wp.customize.control( 'clc-options[logo-text-size]' ),
                        logoImage          = wp.customize.control( 'clc-options[custom-logo]' ),
                        logoWidth          = wp.customize.control( 'clc-options[logo-width]' ),
                        logoHeight         = wp.customize.control( 'clc-options[logo-height]' )

                    if ( '1' == to ) {
                        logoTextColor.toggle( true );
                        logoTextColorHover.toggle( true );
                        logoTextSize.toggle( true );

                        logoImage.toggle( false );
                        logoWidth.toggle( false );
                        logoHeight.toggle( false );
                    }else{
                        logoTextColor.toggle( false );
                        logoTextColorHover.toggle( false );
                        logoTextSize.toggle( false );

                        logoImage.toggle( true );
                        logoWidth.toggle( true );
                        logoHeight.toggle( true );
                    }
                });
            });

        });

    }
})( jQuery );
