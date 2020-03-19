"use strict";

var currentRequest = null;

(function ($) {
	"use strict";

	window.BP_Profile_Field_repeater = {

		init: function init() {
			this.bppfr_add_more_field();
		},

		bppfr_add_more_field : function() {

			$( document ).on( 'click', '.bp_add_more_field', function() {

				var field_id    = $( this ).data( 'field_id' ),
				    clone_field = $( '.clone_field_' + field_id + ' .clone_field' ).clone();

				$( '.more_data_' + field_id ).append( clone_field );

			} );

		},

	};

	$(document).on('ready', function () {
		BP_Profile_Field_repeater.init();
	});

})(jQuery);