(function( $, settingsPageConfig ) {

	'use strict';

	Vue.config.devtools = true;

	window.KavaSettingsPage = new Vue( {
		el: '#kava-settings-page',

		data: {
			pageOptions: settingsPageConfig.settingsData,
			preparedOptions: {},
			savingStatus: false,
			ajaxSaveHandler: null
		},

		mounted: function() {
			this.$el.className = 'is-mounted';
		},

		watch: {
			pageOptions: {
				handler( options ) {
					var prepared = {};

					for ( var option in options ) {

						if ( options.hasOwnProperty( option ) ) {
							prepared[ option ] = options[option]['value'];
						}
					}

					this.preparedOptions = prepared;

					this.saveOptions();
				},
				deep: true
			}
		},

		methods: {

			saveOptions: function() {

				var self = this;

				self.savingStatus = true;

				self.ajaxSaveHandler = $.ajax( {
					type: 'POST',
					url: ajaxurl,
					dataType: 'json',
					data: {
						options: self.preparedOptions,
						action: 'save_settings'
					},
					beforeSend: function( jqXHR, ajaxSettings ) {
						if ( null !== self.ajaxSaveHandler ) {
							self.ajaxSaveHandler.abort();
						}
					},
					success: function( response, textStatus, jqXHR ) {
						self.savingStatus = false;

						if ( 'success' === response.status ) {
							self.$CXNotice.add( {
								message: response.message,
								type: 'success',
								duration: 3000
							} );
						}

						if ( 'error' === response.status ) {
							self.$CXNotice.add( {
								message: response.message,
								type: 'error',
								duration: 3000
							} );
						}
					}
				} );
			},
		}
	} );

})( jQuery, window.KavaSettingsPageConfig );
