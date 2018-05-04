(function( $ ) {
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
      } );
    } );

    wp.customize.controlConstructor[ 'clc-templates' ] = wp.customize.Control.extend( {
      ready: function() {
        var control = this;

        this.container.on( 'change', 'input:radio', function() {
          var template = $( this ).val();

          control.loadTemplate( 'default' );

          if ( 'default' !== template ) {
            control.loadTemplate( template );
          }
          
        } );
      },
      loadTemplate: function( option_name ) {
        var control = this,
            options = control.params.options[ option_name ];
        
            

            $.each( options, function( index, option ) {
              var currentControl = wp.customize.control( option.name ),
                  defaultValue = currentControl.settings.default();

              if ( 'default' == option_name ) {
                currentControl.setting( option.value );
              }else{
                currentControl.setting( option.value );
              }

            });
      }
    } );

    wp.customize.controlConstructor[ 'clc-range-slider' ] = wp.customize.Control.extend( {
      ready: function() {
        var control = this,
            controlField = control.container.find( 'input.clc-slider' ),
            controlSlider = control.container.find( 'div.clc-slider' ),
            controlSliderData = control.params.choices;

        controlSlider.slider( {
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
            control.setting.set( ui.value );
          }
        } );
      }
    } );

    wp.customize.controlConstructor[ 'clc-button-group' ] = wp.customize.Control.extend({
      ready: function() {
        var control = this;
        control.container.on( 'click', '.colorlib-login-customizer-control-group > a', function(){
          var value = $( this ).attr( 'data-value' );
          $( this ).siblings().removeClass( 'active' );
          $( this ).addClass( 'active' );

          control.setting.set( value );
        });
      }
    });

    wp.customize.controlConstructor[ 'clc-color-picker' ] = wp.customize.Control.extend({
      ready: function() {
        var control = this,
            updating = false,
            clear = control.container.find( 'a.clc-color-picker-default' ),
        	input = $( control.container ).find( '.clc-color-picker' );

        input.minicolors({
          format : 'rgb',
          opacity : true,
          keywords: 'transparent, initial, inherit',
          change : function(value, opacity) {
            updating = true;
            control.setting.set( input.minicolors( 'rgbaString' ) );
            updating = false;
          }
        });


	    if ( clear.length > 0 ) {
	      clear.on( 'click', function( e ) {
	      		var defaultValue = jQuery( this ).attr( 'data-default' );
		      	e.preventDefault();

		      	input.minicolors( 'value', defaultValue );
		      	updating = true;
            	control.setting.set( defaultValue );
            	updating = false;
		    } );
	    }

        // Whenever the setting's value changes, refresh the preview.
        control.setting.bind( function ( value ) {

          	// Bail if the update came from the control itself.
          	if ( updating ) {
            	return;
          	}

          	input.minicolors( 'value', value );

        } );
      },
    });

    // Listen for previewer events
    wp.customize.bind( 'ready', function() {
      wp.customize.previewer.bind( 'clc-focus-section', function( sectionName ) {
        var section = wp.customize.section( sectionName );

        if ( undefined !== section ) {
          section.focus();
        }
      } );

      wp.customize( 'clc-options[columns]', function( value ) {
 
        value.bind( function( to ) {
            var align_control = wp.customize.control( 'clc-options[form-column-align]' ),
                background_control = wp.customize.control( 'clc-options[custom-background-form]' ),
                background_color_control = wp.customize.control( 'clc-options[custom-background-color-form]' );
            if ( '2' == to ) {
                align_control.toggle( true );
                background_control.toggle( true );
                background_color_control.toggle( true );
            }else{
                align_control.toggle( false );
                background_control.toggle( false );
                background_color_control.toggle( false );
            }
        } );
      } );

    } );

  }
})( jQuery );
