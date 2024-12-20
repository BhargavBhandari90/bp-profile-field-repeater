<?php
/**
 * Class for admin methods for field repeater.
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
if ( ! class_exists( 'BP_Profile_Field_Repeater_Admin' ) ) {

	/**
	 * Calls for admin methods.
	 */
	class BP_Profile_Field_Repeater_Admin {

		/**
		 * Field ID.
		 *
		 * @var int ID of field.
		 */
		public $id;

		/**
		 * Whether values from this field are repeater or not.
		 *
		 * @var string
		 */
		public $field_is_repeater;

		/**
		 * Constructor for class.
		 */
		public function __construct() {

			// Bail, if anything goes wrong.
			// if ( function_exists( 'buddypress' ) ) {
			// 	return;
			// }

			// Set field id.
			$this->id = filter_input( INPUT_GET, 'field_id', FILTER_SANITIZE_NUMBER_INT );

			// Add settings.
			add_action( 'xprofile_field_after_sidebarbox', array( $this, 'bppfr_field_repeater_setting' ) );

			// Save setting.
			add_action( 'xprofile_fields_saved_field', array( $this, 'bppfr_save_repeater_setting' ) );

			// Show label.
			add_action( 'xprofile_admin_field_name_legend', array( $this, 'bppfr_repeater_label' ) );

		}

		/**
		 * Show box for selecting field as repeater or not in sidebar.
		 *
		 * @return void
		 */
		public function bppfr_field_repeater_setting() {

			?>

			<div class="postbox">
				<h2><?php esc_html_e( 'Is the field repeater?', 'bp-field-repeater' ); ?></h2>
				<div class="inside">
					<p class="description"><?php esc_html_e( 'Make this field as a repeater.', 'bp-field-repeater' ); ?></p>

					<p>
						<label for="field-is-repeater" class="screen-reader-text">
						<?php
							/* translators: accessibility text */
							esc_html_e( 'Is the field repeater?', 'bp-field-repeater' );
						?>
						</label>
						<select name="field_is_repeater" id="field-is-repeater">
							<option value="no" <?php selected( $this->get_is_field_repeater(), 'no' ); ?>><?php esc_html_e( 'No', 'bp-field-repeater' ); ?></option>
							<option value="yes" <?php selected( $this->get_is_field_repeater(), 'yes' ); ?>><?php esc_html_e( 'Yes', 'bp-field-repeater' ); ?></option>
						</select>
					</p>
					<p class="description"><?php esc_html_e( 'Applicable only for Text Box & Number.', 'bp-field-repeater' ); ?></p>
				</div>
			</div>

			<?php
		}

		/**
		 * Get whether the field values are repeater or not.
		 *
		 * @return string Yes | No
		 */
		public function get_is_field_repeater() {
			if ( ! isset( $this->field_is_repeater ) ) {

				$field_is_repeater = bp_xprofile_get_meta( $this->id, 'field', 'field_is_repeater' );

				if ( '' === $field_is_repeater ) {
					$this->field_is_repeater = 'no';
				} else {
					$this->field_is_repeater = $field_is_repeater;
				}
			}

			/**
			 * Filters the repeater property of the field.
			 *
			 * @since 6.0.0
			 *
			 * @param bool              $field_is_repeater The repeater property of the field.
			 * @param BP_XProfile_Field $this Field object.
			 */
			return apply_filters( 'bp_xprofile_field_field_is_repeater', $this->field_is_repeater, $this );
		}

		/**
		 * Save repeater setting.
		 *
		 * @param  object $field Field object.
		 * @return void
		 */
		public function bppfr_save_repeater_setting( $field ) {

			// Bail, if anything goes wrong.
			if ( empty( $field ) || ! function_exists( 'bp_xprofile_update_field_meta' ) ) {
				return;
			}

			// Save repeater settings.
			if ( isset( $_POST['field_is_repeater'] ) &&
				 'yes' === wp_unslash( $_POST['field_is_repeater'] ) &&
				 bppfr_is_valid_repeater_field( $field->id ) ) {

				bp_xprofile_update_field_meta( $field->id, 'field_is_repeater', 'yes' );

			} else {
				bp_xprofile_update_field_meta( $field->id, 'field_is_repeater', 'no' );
			}

		}

		/**
		 * Show repeater label to identify repeater field in fields list.
		 *
		 * @param  object $field Field object.
		 * @return void
		 */
		public function bppfr_repeater_label( $field ) {

			// Bail, if anything goes wrong.
			if ( empty( $field ) ) {
				return;
			}

			// Get if field is repeater.
			$field_is_repeater = bp_xprofile_get_meta( $field->id, 'field', 'field_is_repeater' );

			// Show label if repeater.
			if ( ! empty( $field_is_repeater ) && 'yes' === $field_is_repeater ) {

				echo sprintf(
					/* translators: %1$s is for repeater label */
					' - <strong>%1$s</strong>',
					esc_html__( 'Repeater', 'bp-field-repeater' )
				);

			}

		}

	}

	new BP_Profile_Field_Repeater_Admin();
}
