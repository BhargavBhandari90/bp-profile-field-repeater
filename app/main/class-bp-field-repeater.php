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

			// Add add button.
			add_action( 'bp_custom_profile_edit_fields_pre_visibility', array( $this, 'bppfr_add_more_field' ) );

			// Set attributes as per repeater field.
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

			// Get field ID.
			$field_id = bp_get_the_profile_field_id();

			// Get value if field is repeater or not.
			$field_is_repeater = bp_xprofile_get_meta( $field_id, 'field', 'field_is_repeater' );

			if ( ! empty( $field_is_repeater ) && 'yes' === $field_is_repeater ) {

				$value      = xprofile_get_field_data( $field_id ); // Get value.
				$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() ); // Get field type.

				echo '<div class="more_data more_data_' . esc_attr( $field_id ) . '">';

				if ( ! empty( $value ) ) {

					$value = maybe_unserialize( $value ); // Make array.

					if ( count( $value ) > 1 ) {

						array_shift( $value ); // Remove first value. First value will display in default field.
						$value = array_values( array_filter( $value ) );

						// Display other values.
						foreach ( $value as $val ) {

							// Markup for other values.
							$this->bppfr_get_clone_html( $field_type, $field_id, $val );
						}
					}
				}
				echo '</div>';

				echo '<div style="display:none" class="clone_field_' . esc_attr( $field_id ) . '">';

				// Markup for clone field.
				$this->bppfr_get_clone_html( $field_type, $field_id );

				echo '</div>';

				// Add button.
				echo sprintf(
					'<a data-field_id="%1$s" href="javascript:void(0)" class="bp_add_more_field">%2$s</a>',
					intval( $field_id ),
					esc_html__( '+ Add', 'bp-field-repeater' )
				);

			}
		}

		/**
		 * Set attributes for repeater field.
		 *
		 * @param  array  $attr  Array of attributes for fields.
		 * @param  string $class Related (PHP) class.
		 * @return array         Modified array of attributes for fields.
		 */
		public function bppfr_field_edit_html_elements( $attr, $class ) {

			$field_name  = $attr['name']; // Field name attribute.
			$field_arr   = explode( '_', $field_name ); // Make array.
			$array_count = count( $field_arr ); // Get total count of array.
			$field_id    = $field_arr[ $array_count - 1 ]; // Get field ID.

			// Get value if field is repeater or not.
			$field_is_repeater = bp_xprofile_get_meta( $field_id, 'field', 'field_is_repeater' );

			// If field is repeater, then manipulate attributes.
			if ( ! empty( $field_is_repeater ) && 'yes' === $field_is_repeater ) {

				// Cleanup the value and make it array.
				$value = maybe_unserialize( htmlspecialchars_decode( $attr['value'] ) );

				// Set first value from array.
				if ( ! empty( $value ) && is_array( $value ) ) {
					$attr['value'] = $value[0];
				}

				// Change name to array form.
				$attr['name'] = $field_name . '[]';
			}

			return $attr;

		}

		/**
		 * Markup for clone field.
		 *
		 * @param  object  $field_type Object of field as per type.
		 * @param  integer $field_id   Field ID.
		 * @param  string  $val         Value of field.
		 * @return void
		 */
		public function bppfr_get_clone_html( $field_type, $field_id, $val = '' ) {

			echo '<div class="clone_field"><p>';

			// Get field html.
			$field_type->edit_field_html(
				array(
					'name'  => 'field_' . $field_id . '[]',
					'value' => $val,
					'id'    => '',
				)
			);

			// Remove button.
			echo sprintf(
				'<a href="javascript:void(0)" class="bp_remove_field">%1$s</a>',
				esc_html__( '- Remove', 'bp-field-repeater' )
			);

			echo '</p></div>'; // End .clone_field.
		}
	}

	new BP_Profile_Field_Repeater_Main();
}
