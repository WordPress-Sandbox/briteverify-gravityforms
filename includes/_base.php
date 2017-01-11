<?php
/**
 * Email Field Settings
 * @since 1.0.0
**/
if ( ! defined( 'WPINC' ) ) { die; }

/* Load Class */
BV_GF_Field_Settings::get_instance();

/**
 * Settings Page
 * @since 1.0.0
 */
class BV_GF_Field_Settings{

	/**
	 * Returns the instance.
	 */
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ) $instance = new self;
		return $instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {

	}



}



