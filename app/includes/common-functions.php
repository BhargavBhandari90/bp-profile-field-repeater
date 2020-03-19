<?php
/**
 * Common functions
 *
 * @package Bp_Profile_Fields_Repeater
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bppfr_is_valid_repeater_field( $field_id = 0 ) {

	// Bail, if anything goes wrong.
	if ( empty( $field_id ) || ! class_exists( 'BP_XProfile_Field' ) ) {
		return false;
	}

	$field = new BP_XProfile_Field( $field_id );

	if ( ! empty( $field ) ) {
		return false;
	}

	$valid_field_tyeps = array(
		'textbox' => true,
	);

	if ( isset( $valid_field_tyeps[ $field->type ] ) ) {
		return true;
	}

	return false;

}