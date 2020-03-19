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

			// Add custom script.
			add_action( 'wp_enqueue_scripts', array( $this, 'bppfr_enqueue_styles_scripts' ), 99 );

			add_action( 'bp_custom_profile_edit_fields_pre_visibility', array( $this, 'bppfr_add_more_field' ) );

			// add_filter( 'bp_get_the_profile_field_input_name', array( $this, 'bppfr_field_name' ) );

			add_filter( 'bp_xprofile_field_edit_html_elements', array( $this, 'bppfr_field_edit_html_elements' ), 10, 2 );
		}

		/**
		 * Add scripts & css related to re-post button.
		 */
		public function bppfr_enqueue_styles_scripts() {

			// Custom plugin script.
			wp_enqueue_script(
				'bp-field-repeater-script',
				BPPFR_URL . 'assets/js/custom-script.js',
				'',
				BPPFR_VERSION,
				true
			);

			// Custom plugin script.
			wp_enqueue_style(
				'bp-field-repeater-style',
				BPPFR_URL . 'assets/css/custom-style.css',
				'',
				BPPFR_VERSION
			);
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

			$field_id = bp_get_the_profile_field_id();

			// Get value if field is repeater or not.
			$field_is_repeater = bp_xprofile_get_meta( $field_id, 'field', 'field_is_repeater' );

			if ( ! empty( $field_is_repeater ) && 'yes' === $field_is_repeater ) {

				$value = xprofile_get_field_data( $field_id );

				$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );

				echo '<div class="more_data more_data_' . esc_attr( $field_id ) . '">';
				if ( ! empty( $value ) ) {
					$value = maybe_unserialize( $value );

					if ( count( $value ) > 1 ) {

						array_shift( $value );

						$value = array_values( array_filter( $value ) );

						foreach ( $value as $val ) {

							echo '<div class="clone_field"><p>';

							$field_type->edit_field_html( array(
								'name'  => 'field_' . $field_id . '[]',
								'value' => $val,
								'id'    => '',
							) );

							echo '</p></div>'; // End .clone_field.
						}
					}

				}
				echo '</div>';

				echo '<div style="display:none" class="clone_field_' . esc_attr( $field_id ) . '">';
				echo '<div class="clone_field"><p>';

				$field_type->edit_field_html( array(
					'name'  => 'field_' . $field_id . '[]',
					'value' => '',
					'id'    => '',
				) );

				echo '</p></div>'; // End .clone_field.
				echo '</div>';

				echo sprintf(
					'<a data-field_id="%1$s" href="javascript:void(0)" class="bp_add_more_field">%2$s</a>',
					intval( $field_id ),
					esc_html__( '+ Add', 'bp-field-repeater' )
				);

			}
		}

		public function bppfr_field_name( $field_name ) {

			$field_arr   = explode( '_' , $field_name );
			$array_count = count( $field_arr );
			$field_id    = $field_arr[ $array_count - 1 ];

			// Get value if field is repeater or not.
			$field_is_repeater = bp_xprofile_get_meta( $field_id, 'field', 'field_is_repeater' );

			if ( ! empty( $field_is_repeater ) && 'yes' === $field_is_repeater ) {
				$field_name = $field_name . '[]';
			}

			return $field_name;

		}

		public function bppfr_field_edit_html_elements( $attr, $class ) {

			$field_name  = $attr['name'];
			$field_arr   = explode( '_' , $field_name );
			$array_count = count( $field_arr );
			$field_id    = $field_arr[ $array_count - 1 ];

			// Get value if field is repeater or not.
			$field_is_repeater = bp_xprofile_get_meta( $field_id, 'field', 'field_is_repeater' );

			if ( ! empty( $field_is_repeater ) && 'yes' === $field_is_repeater ) {

				$value = maybe_unserialize( htmlspecialchars_decode( $attr['value'] ) );
				if ( ! empty( $value ) ) {
					$attr['value'] = $value[0];
				}

				$attr['name']  = $field_name . '[]';
			}

			return $attr;

		}
	}

	new BP_Profile_Field_Repeater_Main();
}
