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
		 * @since 1.0.0
		 * @var int ID of field.
		 */
		public $id;

		/**
		 * Whether values from this field are autolinked to directory searches.
		 *
		 * @var string
		 */
		public $field_is_repeater;

		/**
		 * Constructor for class.
		 */
		public function __construct() {

			if ( function_exists( 'buddypress' ) ) {
				return;
			}

			$this->id = filter_input( INPUT_GET, 'field_id', FILTER_SANITIZE_NUMBER_INT );

			// Add settings.
			add_action( 'xprofile_field_after_sidebarbox', array( $this, 'bppfr_field_repeater_setting' ) );

			add_action( 'xprofile_fields_saved_field', array( $this, 'bppfr_save_repeater_setting' ) );

		}

		public function bppfr_field_repeater_setting() {

			?>

			<div class="postbox">
				<h2><?php esc_html_e( 'Is the field repeater?', 'bp-field-repeater' ); ?></h2>
				<div class="inside">
					<p class="description"><?php esc_html_e( 'Make this field as a repeater.', 'bp-field-repeater' ); ?></p>

					<p>
						<label for="field-is-repeater" class="screen-reader-text"><?php
							/* translators: accessibility text */
							esc_html_e( 'Is the field repeater?', 'bp-field-repeater' );
						?></label>
						<select name="field_is_repeater" id="field-is-repeater">
							<option value="no" <?php selected( $this->get_is_field_repeater(), 'no' ); ?>><?php esc_html_e( 'No', 'bp-field-repeater' ); ?></option>
							<option value="yes" <?php selected( $this->get_is_field_repeater(), 'yes' ); ?>><?php esc_html_e( 'Yes', 'bp-field-repeater' ); ?></option>
						</select>
					</p>
				</div>
			</div>

			<?php
		}

		/**
		 * Get whether the field values should be auto-linked to a directory search.
		 *
		 * Lazy-loaded to reduce overhead.
		 *
		 * Defaults to true for multi and default fields, false for single fields.
		 *
		 * @since 2.5.0
		 *
		 * @return bool
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
			//echo '+++'.$this->field_is_repeater.'---';
			return apply_filters( 'bp_xprofile_field_field_is_repeater', $this->field_is_repeater, $this );
		}

		public function bppfr_save_repeater_setting( $field ) {

			// Save autolink settings.
			if ( isset( $_POST['field_is_repeater'] ) && 'yes' === wp_unslash( $_POST['field_is_repeater'] ) ) {
				bp_xprofile_update_field_meta( $field->id, 'field_is_repeater', 'yes' );
			} else {
				bp_xprofile_update_field_meta( $field->id, 'field_is_repeater', 'no' );
			}

		}

	}

	new BP_Profile_Field_Repeater_Admin();
}
