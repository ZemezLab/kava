/**
 * Interface Builder
 */
;( function( $, underscore ) {

	'use strict';

	var cxInterfaceBuilder = {

		init: function() {
			// Component Init
			this.component.init();
			$( document ).on( 'cxFramework:interfaceBuilder:component', this.component.init.bind( this.component ) );

			// Control Init
			this.control.init();
			$( document ).on( 'cxFramework:interfaceBuilder:control', this.control.init.bind( this.control ) );
		},

		component: {
			tabClass:           '.cx-tab',
			accordionClass:     '.cx-accordion',
			toggleClass:        '.cx-toggle',

			buttonClass:        '.cx-component__button',
			contentClass:       '.cx-settings__content',

			buttonActiveClass:  'active',
			showClass:          'show',

			localStorage:        {},

			controlConditions:   window.cxInterfaceBuilder.conditions || {},

			controlValues:       window.cxInterfaceBuilder.fields || {},

			conditionState:      {},

			init: function () {
				this.localStorage = this.getState() || {};

				this.componentInit( this.tabClass );
				this.componentInit( this.accordionClass );
				this.componentInit( this.toggleClass );

				this.addEvent();
				this.conditionsHandleInit();
			},

			addEvent: function() {
				$( 'body' )
					.off( 'click.cxInterfaceBuilder' )
					.on( 'click.cxInterfaceBuilder',
						this.tabClass + ' ' + this.buttonClass + ', ' +
						this.toggleClass + ' ' + this.buttonClass + ', ' +
						this.accordionClass + ' ' + this.buttonClass,

						this.componentClick.bind( this )
					);
			},

			conditionsHandleInit: function() {
				var self = this;

				$( window ).on( 'cx-switcher-change', function( event ) {
					var controlName   = event.controlName,
						controlStatus = event.controlStatus;

					self.updateConditionRules( controlName, controlStatus );
					self.renderConditionRules();
				});

				$( window ).on( 'cx-select-change', function( event ) {
					var controlName   = event.controlName,
						controlStatus = event.controlStatus;

					self.updateConditionRules( controlName, controlStatus );
					self.renderConditionRules();
				});

				$( window ).on( 'cx-select2-change', function( event ) {
					var controlName   = event.controlName,
						controlStatus = event.controlStatus;

					self.updateConditionRules( controlName, controlStatus );
					self.renderConditionRules();
				});

				$( window ).on( 'cx-radio-change', function( event ) {
					var controlName   = event.controlName,
						controlStatus = event.controlStatus;

					self.updateConditionRules( controlName, controlStatus );
					self.renderConditionRules();
				});

				$( window ).on( 'cx-checkbox-change', function( event ) {
					var controlName   = event.controlName,
						controlStatus = event.controlStatus,
						updatedStatus = {};

					$.each( controlStatus[ controlName ], function( checkbox, value ) {
						updatedStatus[ checkbox ] = cxInterfaceBuilder.utils.filterBoolValue( value );
					} );

					self.updateConditionRules( controlName, updatedStatus );
					self.renderConditionRules();
				});

				this.generateConditionRules();
				self.renderConditionRules();

			},

			generateConditionRules: function() {
				var self = this;

				$.each( this.controlConditions, function( control, conditions ) {
					$.each( conditions, function( control, value ) {
						if ( self.controlValues.hasOwnProperty( control ) ) {
							self.conditionState[ control ] = self.controlValues[ control ];
						}
					} );
				} );
			},

			updateConditionRules: function( name, status ) {
				this.conditionState[ name ] = status;
			},

			renderConditionRules: function() {
				var self = this;

				$.each( this.controlConditions, function( control, conditions ) {
					var $selector = $( '.cx-control[data-control-name="' + control + '"]' ),
						hidden    = true;

					$selector.addClass( 'cx-control-hidden' );

					$.each( conditions, function( control, value ) {
						hidden = true;

						if ( self.conditionState.hasOwnProperty( control ) ) {
							var type = typeof value;

							switch ( type ) {
								case 'string':
									if ( self.conditionState[control] === value ) {
										hidden = false;
									}
									break;
								case 'boolean':
									if ( self.conditionState[control].toString() === value.toString() ) {
										hidden = false;
									}
									break;
								default :
									if ( -1 !== value.indexOf( self.conditionState[control] ) ) {
										hidden = false;
									}
									break;
							}

							if ( 'object' === typeof self.conditionState[ control ] ) {
								hidden = false;

								$.each( self.conditionState[ control ], function( prop, val ) {

									if ( ! val && -1 !== value.indexOf( prop ) ) {
										hidden = true;

										return false;
									}
								} );
							}

						}

						if ( hidden ) {
							return false;
						}

					} );

					if ( hidden ) {
						$selector.addClass( 'cx-control-hidden' );
					} else {
						$selector.removeClass( 'cx-control-hidden' );
					}
				} );
			},

			componentInit: function( componentClass ) {
				var _this = this,
					components = $( componentClass ),
					componentId = null,
					button = null,
					contentId = null,
					notShow = '';

				components.each( function( index, component ) {
					component   = $( component );
					componentId = component.data( 'compotent-id' );

					switch ( componentClass ) {
						case _this.toggleClass:
							if ( _this.localStorage[ componentId ] && _this.localStorage[ componentId ].length ) {
								notShow = _this.localStorage[ componentId ].join( ', ' );
							}

							$( _this.contentClass, component )
								.not( notShow )
								.addClass( _this.showClass )
								.prevAll( _this.buttonClass )
								.addClass( _this.buttonActiveClass );
						break;

						case _this.tabClass:
						case _this.accordionClass:
							if ( _this.localStorage[ componentId ] ) {
								contentId = _this.localStorage[ componentId ][ 0 ];
								button = $( '[data-content-id="' + contentId + '"]', component );
							} else {
								button = $( _this.buttonClass, component ).eq( 0 );
								contentId = button.data( 'content-id' );
							}

							_this.showElement( button, component, contentId );
						break;
					}
				} );
			},

			componentClick: function( event ) {
				var $target      = $( event.target ),
					$parent      = $target.closest( this.tabClass + ', ' + this.accordionClass + ', ' + this.toggleClass ),
					expr          = new RegExp( this.tabClass + '|' + this.accordionClass + '|' + this.toggleClass ),
					componentName = $parent[0].className.match( expr )[ 0 ].replace( ' ', '.' ),
					contentId     = $target.data( 'content-id' ),
					componentId   = $parent.data( 'compotent-id' ),
					activeFlag    = $target.hasClass( this.buttonActiveClass ),
					itemClosed;

				switch ( componentName ) {
					case this.tabClass:
						if ( ! activeFlag ) {
							this.hideElement( $parent );
							this.showElement( $target, $parent, contentId );

							this.localStorage[ componentId ] = new Array( contentId );
							this.setState();
						}
					break;

					case this.accordionClass:
						this.hideElement( $parent );

						if ( ! activeFlag ) {
							this.showElement( $target, $parent, contentId );

							this.localStorage[ componentId ] = new Array( contentId );
						} else {
							this.localStorage[ componentId ] = {};
						}
						this.setState();
					break;

					case this.toggleClass:
						$target
							.toggleClass( this.buttonActiveClass )
							.nextAll( contentId )
							.toggleClass( this.showClass );

						if ( Array.isArray( this.localStorage[ componentId ] ) ) {
							itemClosed = this.localStorage[ componentId ].indexOf( contentId );

							if ( -1 !== itemClosed ) {
								this.localStorage[ componentId ].splice( itemClosed, 1 );
							} else {
								this.localStorage[ componentId ].push( contentId );
							}

						} else {
							this.localStorage[ componentId ] = new Array( contentId );
						}

						this.setState();
					break;
				}
				$target.blur();

				return false;
			},

			showElement: function ( button, holder, contentId ) {
				button
					.addClass( this.buttonActiveClass );

				holder
					.data( 'content-id', contentId );

				$( contentId, holder )
					.addClass( this.showClass );
			},

			hideElement: function ( holder ) {
				var contsntId = holder.data( 'content-id' );

				$( '[data-content-id="' + contsntId + '"]', holder )
					.removeClass( this.buttonActiveClass );

				$( contsntId, holder )
					.removeClass( this.showClass );
			},

			getState: function() {
				try {
					return JSON.parse( localStorage.getItem( 'interface-builder' ) );
				} catch ( e ) {
					return false;
				}
			},

			setState: function() {
				try {
					localStorage.setItem( 'interface-builder', JSON.stringify( this.localStorage ) );
				} catch ( e ) {
					return false;
				}
			}
		},

		control: {
			init: function () {
				this.switcher.init();
				this.checkbox.init();
				this.radio.init();
				this.slider.init();
				this.select.init();
				this.media.init();
				this.colorpicker.init();
				this.iconpicker.init();
				this.dimensions.init();
				this.repeater.init();
			},

			// CX-Switcher
			switcher: {
				switcherClass: '.cx-switcher-wrap',
				trueClass: '.cx-input-switcher-true',
				falseClass: '.cx-input-switcher-false',

				init: function() {
					$( 'body' ).on( 'click.cxSwitcher', this.switcherClass, this.switchState.bind( this ) );
				},

				switchState: function( event ) {
					var $this       = $( event.currentTarget ),
						$inputTrue  = $( this.trueClass, $this ),
						$inputFalse = $( this.falseClass, $this ),
						status      = $inputTrue[0].checked,
						$parent     = $( event.currentTarget ).closest( '.cx-control-switcher' ),
						name        = $parent.data( 'control-name' );

					$inputTrue.prop( 'checked', ( status ) ? false : true );
					$inputFalse.prop( 'checked', ( ! status ) ? false : true );

					status = $inputTrue[0].checked;

					$( window ).trigger( {
						type: 'cx-switcher-change',
						controlName: name,
						controlStatus: status
					} );
				}

			},//End CX-Switcher

			// CX-Checkbox
			checkbox: {
				inputClass: '.cx-checkbox-input[type="hidden"]:not([name*="__i__"])',
				itemClass: '.cx-checkbox-label, .cx-checkbox-item',

				init: function() {
					$( 'body' ).on( 'click.cxCheckbox', this.itemClass, this.switchState.bind( this ) );
				},

				switchState: function( event ) {
					var $_input    = $( event.currentTarget ).siblings( this.inputClass ),
						status     = $_input[0].checked,
						$parent    = $( event.currentTarget ).closest( '.cx-control-checkbox' ),
						name       = $parent.data( 'control-name' ),
						statusData = {};

					$_input.val( ! status ? 'true' : 'false' ).attr( 'checked', ! status ? true : false );

					statusData = cxInterfaceBuilder.utils.serializeObject( $parent );

					$( window ).trigger( {
						type: 'cx-checkbox-change',
						controlName: name,
						controlStatus: statusData
					} );
				}
			},//End CX-Checkbox

			// CX-Radio
			radio: {
				inputClass: '.cx-radio-input:not([name*="__i__"])',

				init: function() {
					$( 'body' ).on( 'click.cxRadio', this.inputClass, this.switchState.bind( this ) );
				},

				switchState: function( event ) {
					var $this   = $( event.currentTarget ),
						$parent = $( event.currentTarget ).closest( '.cx-control-radio' ),
						name    = $parent.data( 'control-name' );

					$( window ).trigger( {
						type: 'cx-radio-change',
						controlName: name,
						controlStatus: $( $this ).val()
					} );
				}
			},//End CX-Radio

			// CX-Slider
			slider: {
				init: function() {
					$( 'body' ).on( 'input.cxSlider change.cxSlider', '.cx-slider-unit, .cx-ui-stepper-input', this.changeHandler.bind( this ) );
				},

				changeHandler: function( event ) {
					var $this            = $( event.currentTarget ),
						$thisVal         = $this.val(),
						$sliderWrapper   = $this.closest( '.cx-slider-wrap' ),
						$sliderContainer = $this.closest( '.cx-ui-container' ),
						$sliderSettings  = $sliderContainer.data( 'settings' ),
						targetClass      = ( ! $this.hasClass( 'cx-slider-unit' ) ) ? '.cx-slider-unit' : '.cx-ui-stepper-input';

					$( targetClass, $sliderWrapper ).val( $thisVal );

					if ( $sliderSettings['range_label'] ) {
						var $rangeLabel = $( '.cx-slider-range-label', $sliderWrapper ),
							rangeLabels = $sliderSettings['range_labels'];

						if ( 0 === +$thisVal ) {
							$rangeLabel.html( rangeLabels[+$thisVal]['label'] );
							$rangeLabel.css( 'color', rangeLabels[+$thisVal]['color'] );

							return false;
						}

						Object.keys(rangeLabels).reduce( function( prev, current, index, array ) {

							if ( ( +$thisVal > +prev && +$thisVal <= +current ) ) {
								$rangeLabel.html( rangeLabels[+current]['label'] );
								$rangeLabel.css( 'color', rangeLabels[+current]['color'] );
							}

							return current;
						} );
					}
				}
			},//End CX-Slider

			// CX-Select
			select: {
				selectClass: '.cx-ui-select[data-filter="false"]:not([name*="__i__"])',
				select2Class: '.cx-ui-select[data-filter="true"]:not([name*="__i__"]), .cx-ui-select[multiple]:not([name*="__i__"])',
				selectClearClass: '.cx-ui-select-clear',

				init: function() {

					$( document )
						.on( 'ready.cxSelect', this.selectRender.bind( this ) )
						.on( 'cx-control-init', this.selectRender.bind( this ) )
						.on( 'click.cxSelect', this.selectClearClass, this.clearSelect );

				},

				clearSelect: function( event ) {
					event.preventDefault();
					$( this ).siblings( 'select' ).val( null ).trigger( 'change' );
				},

				selectRender: function( event ) {
					var $target = ( event._target ) ? event._target : $( 'body' );

					$( this.selectClass, $target ).each( this.selectInit.bind( this ) );
					$( this.select2Class, $target ).each( this.select2Init.bind( this ) );
				},

				selectInit: function ( index, element ) {
					var $this = $( element ),
						name  = $this.attr( 'id' );

					$this.change( function( event ) {
						$( window ).trigger( {
							type: 'cx-select-change',
							controlName: name,
							controlStatus: $( event.target ).val()
						} );
					});
				},

				select2Init: function ( index, element ) {
					var $this    = $( element ),
						name     = $this.attr( 'id' ),
						settings = { placeholder: $this.data( 'placeholder' ) },
						postType = $this.data( 'post-type' ),
						exclude  = $this.data( 'exclude' ),
						action   = $this.data( 'action' );

					if ( action && postType ) {

						settings.ajax = {
							url: function() {
								return ajaxurl + '?action=' + action + '&post_type=' + $this.data( 'post-type' ) + '&exclude=' + exclude;
							},
							dataType: 'json'
						};

						settings.minimumInputLength = 3;

					}

					$this.select2( settings ).on( 'change.cxSelect2', function( event ) {
						$( window ).trigger( {
							type: 'cx-select2-change',
							controlName: name,
							controlStatus: $( event.target ).val()
						} );
					} );
				}
			},//End CX-Select

			// CX-Media
			media: {
				init: function() {
					$( document )
						.on( 'ready.cxMedia', this.mediaRender.bind( this ) )
						.on( 'cx-control-init', this.mediaRender.bind( this ) );
				},

				mediaRender: function( event ) {
					var target   = ( event._target ) ? event._target : $( 'body' ),
						$buttons = $( '.cx-upload-button', target );

					$buttons.each( function() {
						var button = $( this ),
							buttonParent = button.closest('.cx-ui-media-wrap'),
							settings = {
								input: $( '.cx-upload-input', buttonParent ),
								img_holder: $( '.cx-upload-preview', buttonParent ),
								title_text: button.data('title'),
								multiple: button.data('multi-upload'),
								library_type: button.data('library-type'),
							},
							cx_uploader = wp.media.frames.file_frame = wp.media({
								title: settings.title_text,
								button: { text: settings.title_text },
								multiple: settings.multiple,
								library : { type : settings.library_type }
							});

						if ( ! buttonParent.has('input[name*="__i__"]')[ 0 ] ) {

							button.off( 'click.cx-media' ).on( 'click.cx-media', function() {
								cx_uploader.open();
								return !1;
							} ); // end click

							if ( button.data( 'multi-upload' ) ) {
								cx_uploader.on( 'open', function() {

									var selection = cx_uploader.state().get( 'selection' );
									var selected  = settings.input.val();

									if ( selected ) {
										selected = selected.split(',');
										selected.forEach( function( imgID ) {
											selection.add( wp.media.attachment( imgID ) );
										} );
									}
								});
							}

							cx_uploader.on('select', function() {
									var attachment     = cx_uploader.state().get( 'selection' ).toJSON(),
										count          = 0,
										input_value    = '',
										new_img_object = $( '.cx-all-images-wrap', settings.img_holder ),
										new_img        = '',
										delimiter      = '';

									if ( settings.multiple ) {
										delimiter = ',';
									}

									while( attachment[ count ] ) {
										var img_data    = attachment[ count ],
											return_data = img_data.id,
											mimeType    = img_data.mime,
											img_src     = '',
											thumb       = '';

											switch (mimeType) {
												case 'image/jpeg':
												case 'image/png':
												case 'image/gif':
														if ( img_data.sizes !== undefined ) {
															img_src = img_data.sizes.thumbnail ? img_data.sizes.thumbnail.url : img_data.sizes.full.url;
														}
														thumb = '<img  src="' + img_src + '" alt="" data-img-attr="' + return_data + '">';
													break;
												case 'image/x-icon':
														thumb = '<span class="dashicons dashicons-format-image"></span>';
													break;
												case 'video/mpeg':
												case 'video/mp4':
												case 'video/quicktime':
												case 'video/webm':
												case 'video/ogg':
														thumb = '<span class="dashicons dashicons-format-video"></span>';
													break;
												case 'audio/mpeg':
												case 'audio/wav':
												case 'audio/ogg':
														thumb = '<span class="dashicons dashicons-format-audio"></span>';
													break;
											}

											new_img += '<div class="cx-image-wrap">'+
														'<div class="inner">'+
															'<div class="preview-holder"  data-id-attr="' + return_data +'"><div class="centered">' + thumb + '</div></div>'+
															'<a class="cx-remove-image" href="#"><i class="dashicons dashicons-no"></i></a>'+
															'<span class="title">' + img_data.title + '</span>'+
														'</div>'+
													'</div>';

										input_value += delimiter + return_data;
										count++;
									}

									settings.input.val( input_value.replace( /(^,)/, '' ) ).trigger( 'change' );
									new_img_object.html( new_img );
								} );

							var removeMediaPreview = function( item ) {
								var buttonParent = item.closest( '.cx-ui-media-wrap' ),
									input         = $( '.cx-upload-input', buttonParent ),
									img_holder    = item.parent().parent( '.cx-image-wrap' ),
									img_attr      = $( '.preview-holder', img_holder ).data( 'id-attr' ),
									input_value   = input.attr( 'value' ),
									pattern       = new RegExp( img_attr + '(,*)', 'i' );

									input_value = input_value.replace( pattern, '' );
									input_value = input_value.replace( /(,$)/, '' );
									input.attr( { 'value': input_value } ).trigger( 'change' );
									img_holder.remove();
							};

							// This function remove upload image
							buttonParent.on( 'click', '.cx-remove-image', function () {
								removeMediaPreview( $( this ) );
								return !1;
							});
						}
					} ); // end each

					// Image ordering
					if ( $buttons[0] ) {
						$('.cx-all-images-wrap', target).sortable( {
							items: 'div.cx-image-wrap',
							cursor: 'move',
							scrollSensitivity: 40,
							forcePlaceholderSize: true,
							forceHelperSize: false,
							helper: 'clone',
							opacity: 0.65,
							placeholder: 'cx-media-thumb-sortable-placeholder',
							start:function(){},
							stop:function(){},
							update: function() {
								var attachment_ids = '';

								$('.cx-image-wrap', this).each(
									function() {
										var attachment_id = $('.preview-holder', this).data( 'id-attr' );
											attachment_ids = attachment_ids + attachment_id + ',';
									}
								);

								attachment_ids = attachment_ids.substr(0, attachment_ids.lastIndexOf(',') );
								$(this).parent().siblings('.cx-element-wrap').find('input.cx-upload-input').val( attachment_ids ).trigger( 'change' );
							}
						} );
					}
				}
			},//End CX-Media

			// CX-Colorpicker
			colorpicker: {
				init: function() {
					$( document )
						.on( 'ready.cxColorpicker', this.render.bind( this ) )
						.on( 'cx-control-init', this.render.bind( this ) );
				},

				render: function( event ) {
					var target = ( event._target ) ? event._target : $( 'body' ),
						input = $( 'input.cx-ui-colorpicker:not([name*="__i__"])', target );

					if ( input[0] ) {
						input.wpColorPicker();
					}
				}
			},//End CX-Colorpicker

			// CX-Iconpicker
			iconpicker: {
				iconSets: {},
				iconSetsKey: 'cx-icon-sets',

				init: function() {
					$( document )
						.on( 'ready.cxIconpicker', this.setIconsSets.bind( this, window.CxIconSets ) )
						.on( 'ready.cxIconpicker', this.render.bind( this ) )
						.on( 'cx-control-init', this.render.bind( this ) );
				},

				setIconsSets: function( iconSets ) {
					var icons,
						_this = this;

					if ( iconSets ) {
						icons  = ( iconSets.response ) ? iconSets.response.CxIconSets : iconSets;

						$.each( icons, function( name, data ) {
							_this.iconSets[name] = data;
						} );

						_this.setState( _this.iconSetsKey, _this.iconSets );
					}
				},

				getIconsSets: function() {
					var iconSets = this.getState( this.iconSetsKey );

					if ( iconSets ) {
						this.iconSets = iconSets;
					}
				},

				render: function( event ) {
					var target = ( event._target ) ? event._target : $( 'body' ),
						$picker = $( '.cx-ui-iconpicker:not([name*="__i__"])', target ),
						$this,
						set,
						setData,
						_this = this;

					if ( $picker[0] ) {
						this.getIconsSets();

						$picker.each( function() {
							$this   = $( this );
							set     = $this.data( 'set' );
							setData = _this.iconSets[set];

							if ( $this.length && setData.icons ) {
								$this.iconpicker({
									icons: setData.icons,
									iconBaseClass: setData.iconBase,
									iconClassPrefix: setData.iconPrefix,
									animation: false,
									fullClassFormatter: function( val ) {
										return setData.iconBase + ' ' + setData.iconPrefix + val;
									}
								}).on( 'iconpickerUpdated', function() {
									$( this ).trigger( 'change' );
								});
							}

							if ( setData ) {
								$( 'head' ).append( '<link rel="stylesheet" type="text/css" href="' + setData.iconCSS + '"">' );
							}
						} );
					}
				},

				getState: function( key ) {
					try {
						return JSON.parse( window.sessionStorage.getItem( key ) );
					} catch ( e ) {
						return false;
					}
				},

				setState: function( key, data ) {
					try {
						window.sessionStorage.setItem( key, JSON.stringify( data ) );
					} catch ( e ) {
						return false;
					}
				}
			},//End CX-Iconpicker

			// CX-Dimensions
			dimensions: {
				container: '.cx-ui-dimensions',
				isLinked: '.cx-ui-dimensions__is-linked',
				units: '.cx-ui-dimensions__unit',
				unitsInput: 'input[name*="[units]"]',
				linkedInput: 'input[name*="[is_linked]"]',
				valuesInput: '.cx-ui-dimensions__val',

				init: function() {
					$( 'body' )
						.on( 'click', this.isLinked, { 'self': this }, this.switchLinked )
						.on( 'click', this.units, { 'self': this }, this.switchUnits )
						.on( 'input', this.valuesInput + '.is-linked', { 'self': this }, this.changeLinked );
				},

				render: function( event ) {

				},

				switchLinked: function( event ) {

					var self       = event.data.self,
						$this      = $( this ),
						$container = $this.closest( self.container ),
						$input     = $container.find( self.linkedInput ),
						$values    = $container.find( self.valuesInput ),
						isLinked   = $input.val();

					if ( 0 === parseInt( isLinked ) ) {
						$input.val(1);
						$this.addClass( 'is-linked' );
						$values.addClass( 'is-linked' );
					} else {
						$input.val(0);
						$this.removeClass( 'is-linked' );
						$values.removeClass( 'is-linked' );
					}

				},

				switchUnits: function( event ) {
					var self       = event.data.self,
						$this      = $( this ),
						unit       = $this.data( 'unit' ),
						$container = $this.closest( self.container ),
						$input     = $container.find( self.unitsInput ),
						$values    = $container.find( self.valuesInput ),
						range      = $container.data( 'range' );

					if ( $this.hasClass( 'is-active' ) ) {
						return;
					}

					$this.addClass( 'is-active' ).siblings( self.units ).removeClass( 'is-active' );
					$input.val( unit );
					$values.attr({
						min: range[ unit ].min,
						max: range[ unit ].max,
						step: range[ unit ].step
					});

				},

				changeLinked: function( event ) {
					var self  = event.data.self,
						$this = $( this ),
						$container = $this.closest( '.cx-ui-dimensions__values' );

					$( self.valuesInput, $container ).val( $this.val() )
				}
			},//End CX-Dimensions

			// CX-Repeater
			repeater: {
				repeaterContainerClass: '.cx-ui-repeater-container',
				repeaterListClass: '.cx-ui-repeater-list',
				repeaterItemClass: '.cx-ui-repeater-item',
				repeaterItemHandleClass: '.cx-ui-repeater-actions-box',
				repeaterTitleClass: '.cx-ui-repeater-title',
				addItemButtonClass: '.cx-ui-repeater-add',
				removeItemButtonClass: '.cx-ui-repeater-remove',
				toggleItemButtonClass: '.cx-ui-repeater-toggle',
				minItemClass: 'cx-ui-repeater-min',
				sortablePlaceholderClass: 'sortable-placeholder',

				init: function() {
					$( document ).on( 'ready.cxRepeat', this.addEvents.bind( this ) );
				},

				addEvents: function() {
					$( 'body' )
						.on( 'click', this.addItemButtonClass, { 'self': this }, this.addItem )
						.on( 'click', this.removeItemButtonClass, { 'self': this }, this.removeItem )
						.on( 'click', this.toggleItemButtonClass, { 'self': this }, this.toggleItem )
						.on( 'change', this.repeaterListClass + ' input, ' + this.repeaterListClass + ' textarea, ' + this.repeaterListClass + ' select', { 'self': this }, this.changeWrapperLable )
						.on( 'sortable-init', { 'self': this }, this.sortableItem );

					$( document )
						.on( 'cx-control-init', { 'self': this }, this.sortableItem );

					this.triggers();
				},

				triggers: function( $target ) {
					$( 'body' ).trigger( 'sortable-init' );

					if ( $target ) {
						$( document ).trigger( 'cx-control-init', { 'target': $target } );
					}

					return this;
				},

				addItem: function( event ) {
					var self        = event.data.self,
						$list       = $( this ).prev( self.repeaterListClass ),
						index       = $list.data( 'index' ),
						tmplName    = $list.data( 'name' ),
						rowTemplate = wp.template( tmplName ),
						widgetId    = $list.data( 'widget-id' ),
						data        = { index: index },
						$parent     = $list.parent().closest( self.repeaterListClass );

					widgetId = '__i__' !== widgetId ? widgetId : $list.attr( 'id' ) ;

					if ( widgetId ) {
						data.widgetId = widgetId;
					}

					if ( $parent.length ) {
						data.parentIndex = parseInt( $parent.data( 'index' ), 10 ) - 1;
					}

					$list.append( rowTemplate( data ) );

					index++;
					$list.data( 'index', index );

					self.triggers( $( self.repeaterItemClass + ':last', $list ) ).stopDefaultEvent( event );
				},

				removeItem: function( event ) {
					var self  = event.data.self,
						$list = $( this ).closest( self.repeaterListClass );

					self.applyChanges( $list );

					$( this ).closest( self.repeaterItemClass ).remove();

					self
						.triggers()
						.stopDefaultEvent( event );
				},

				toggleItem: function( event ) {
					var self = event.data.self,
						$container = $( this ).closest( self.repeaterItemClass );

					$container.toggleClass( self.minItemClass );

					self.stopDefaultEvent( event );
				},

				sortableItem: function( event ) {
					var self  = event.data.self,
						$list = $( self.repeaterListClass ),
						$this,
						initFlag;

					$list.each( function( indx, element ) {
						$this    = $( element );
						initFlag = $( element ).data( 'sortable-init' );

						if ( ! initFlag ) {
							$this.sortable( {
								items: self.repeaterItemClass,
								handle: self.repeaterItemHandleClass,
								cursor: 'move',
								scrollSensitivity: 40,
								forcePlaceholderSize: true,
								forceHelperSize: false,
								distance: 2,
								tolerance: 'pointer',
								helper: function( event, element ) {
									return element.clone()
										.find( ':input' )
										.attr( 'name', function( i, currentName ) {
											return 'sort_' + parseInt( Math.random() * 100000, 10 ).toString() + '_' + currentName;
										} )
										.end();
								},
								opacity: 0.65,
								placeholder: self.sortablePlaceholderClass,
								create: function() {
									$this.data( 'sortable-init', true );
								},
								update: function( event ) {
									var target = $( event.target );

									self.applyChanges( target );
								}
							} );
						} else {
							$this.sortable( 'refresh' );
						}
					} );
				},

				changeWrapperLable: function( event ) {
					var self        = event.data.self,
						$list       = $( self.repeaterListClass ),
						titleFilds  = $list.data( 'title-field' ),
						$this       = $( this ),
						value,
						parentItem;

					if ( titleFilds && $this.closest( '.' + titleFilds + '-wrap' )[0] ) {
						value       = $this.val(),
						parentItem  = $this.closest( self.repeaterItemClass );

						$( self.repeaterTitleClass, parentItem ).html( value );
					}

					self.stopDefaultEvent( event );
				},

				applyChanges: function( target ) {
					if ( undefined !== wp.customize ) {
						$( 'input[name]:first, select[name]:first', target ).change();
					}

					return this;
				},

				stopDefaultEvent: function( event ) {
					event.preventDefault();
					event.stopImmediatePropagation();
					event.stopPropagation();

					return this;
				}

			}
		},

		utils: {

			/**
			 * Serialize form into
			 *
			 * @return {Object}
			 */
			serializeObject: function( selector ) {

				var self = this,
					json = {},
					pushCounters = {},
					patterns = {
						'validate': /^[a-zA-Z][a-zA-Z0-9_-]*(?:\[(?:\d*|[a-zA-Z0-9_-]+)\])*$/,
						'key':      /[a-zA-Z0-9_-]+|(?=\[\])/g,
						'push':     /^$/,
						'fixed':    /^\d+$/,
						'named':    /^[a-zA-Z0-9_-]+$/
					},
					serialized;

				this.build = function( base, key, value ) {
					base[ key ] = value;

					return base;
				};

				this.push_counter = function( key ) {
					if ( undefined === pushCounters[ key ] ) {
						pushCounters[ key ] = 0;
					}

					return pushCounters[ key ]++;
				};

				if ( 'FORM' === selector[0].tagName ) {
					serialized = selector.serializeArray();
				} else {
					serialized = selector.find( 'input, textarea, select' ).serializeArray();
				}

				$.each( serialized, function() {
					var k, keys, merge, reverseKey;

					// Skip invalid keys
					if ( ! patterns.validate.test( this.name ) ) {
						return;
					}

					keys = this.name.match( patterns.key );
					merge = this.value;
					reverseKey = this.name;

					while ( undefined !== ( k = keys.pop() ) ) {

						// Adjust reverseKey
						reverseKey = reverseKey.replace( new RegExp( '\\[' + k + '\\]$' ), '' );

						// Push
						if ( k.match( patterns.push ) ) {
							merge = self.build( [], self.push_counter( reverseKey ), merge );
						} else if ( k.match( patterns.fixed ) ) {
							merge = self.build( [], k, merge );
						} else if ( k.match( patterns.named ) ) {
							merge = self.build( {}, k, merge );
						}
					}

					json = $.extend( true, json, merge );
				});

				return json;
			},

			/**
			 * Boolean value check
			 *
			 * @return {Boolean}
			 */
			filterBoolValue: function( value ) {
				var num = +value;

				return ! isNaN( num ) ? !! num : !! String( value ).toLowerCase().replace( !!0, '' );
			}
		}

	};

	cxInterfaceBuilder.init();

}( jQuery, window._ ) );
