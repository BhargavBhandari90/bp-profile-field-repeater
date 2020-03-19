"use strict";

(function ($) {
	"use strict";

	window.BP_Profile_Field_repeater = {

		init: function init() {
			this.bppfr_add_more_field();
			this.bppfr_remove_field();
		},

		/**
		 * Add more field.
		 */
		bppfr_add_more_field : function() {

			$( document ).on( 'click', '.bp_add_more_field', function() {

				var field_id    = $( this ).data( 'field_id' ),
				    clone_field = $( '.clone_field_' + field_id + ' .clone_field' ).clone();

				// Append cloned field.
				$( '.more_data_' + field_id ).append( clone_field );

			} );

		},

		/**
		 * Remove field.
		 */
		bppfr_remove_field : function() {

			$( document ).on( 'click', '.bp_remove_field', function() {

				if ( $( this ).parents( '.clone_field' ).length > 0 ) {
					$( this ).parents( '.clone_field' ).remove();
				}

			} );

		},

	};

	$(document).on('ready', function () {
		BP_Profile_Field_repeater.init();
	});

})(jQuery);
