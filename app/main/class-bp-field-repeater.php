<?php
/**
 * Class for methods for field repeater.
 *
 * @package Bp_Profile_Fields_Repeater
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class is exist, then don't execute this.
if ( ! class_exists( 'BP_Profile_Field_Repeater_Main' ) ) {

	/**
	 * Calls for main methods.
	 */
	class BP_Profile_Field_Repeater_Main {

		/**
		 * Constructor for class.
		 */
		public function __construct() {

			if ( function_exists( 'buddypress' ) ) {
				return;
			}

			add_action( 'bp_custom_profile_edit_fields_pre_visibility', array( $this, 'bppfr_add_more_field' ) );
		}

		/**
		 * Add more field button which is selected as repeater from backend.
		 *
		 * @return void.
		 */
		public function bppfr_add_more_field() {

			// Bail, if anything goes wrong.
			if ( ! function_exists( 'bp_get_the_profile_field_id' ) || ! function_exists( 'bp_xprofile_get_meta' ) ) {
				return;
			}

			// Get value if field is repeater or not.
			$field_is_repeater = bp_xprofile_get_meta( bp_get_the_profile_field_id(), 'field', 'field_is_repeater' );

			if ( ! empty( $field_is_repeater ) && 'yes' === $field_is_repeater ) {

				echo sprintf(
					'<a data-field_id="%1$s" href="javascript:void(0)" class="bp_add_more_field">%2$s</a>',
					intval( bp_get_the_profile_field_id() ),
					esc_html__( 'Add more field', 'bp-field-repeater' )
				);

			}
		}
	}

	new BP_Profile_Field_Repeater_Main();
}
