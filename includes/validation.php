<?php
/**
 * Email Field Settings
 * @since 1.0.0
**/
if ( ! defined( 'WPINC' ) ) { die; }

/* Load Class */
BV_GF_Validation::get_instance();

/**
 * Field Validation
 * @since 1.0.0
 */
class BV_GF_Validation{

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

		/* Filter GF validation */
		add_filter( 'gform_field_validation', array( $this, 'email_validation' ), 10, 4 );
	}

	/**
	 * Email Validation
	 * @since 1.0.0
	 */
	public function email_validation( $result, $value, $form, $field ){

		/* Bail if not email field. */
		if( 'email' != $field->type ){
			return $result;
		}

		/* Bail if no API Key */
		$api_key = esc_html( get_option( 'bv_gf_api_key' ) );
		if( ! $api_key ){
			return $result;
		}

		/* Allow Disposable Email Option */
		$allow_dp = get_option( 'bv_gf_allow_disposable' ) ?  true : false;
		if( 'yes' == $field->bv_gf_allow_disposable ){
			$allow_dp = true;
		}
		elseif( 'no' == $field->bv_gf_allow_disposable ){
			$allow_dp = false;
		}

		/* Check if validation enabled */
		$validate = get_option( 'bv_gf_enable' ) ? true : false;
		if( 'yes' == $field->bv_gf_enable ){
			$validate = true;
		}
		elseif( 'no' == $field->bv_gf_enable ){
			$validate = false;
		}

		/* Validate */
		if( $validate ){
			return $this->validate_email( $result, $value, $allow_dp );
		}

		return $result;
	}


	/**
	 * Validate Email
	 * Only use this function after making sure all requirements complete.
	 * @since 1.0.0
	 */
	public function validate_email( $result, $value, $allow_dp ){

		/* Bail if no value */
		if( ! $value ){
			return $result;
		}

		/* Bail if no API Key */
		$api_key = esc_html( get_option( 'bv_gf_api_key' ) );
		if( ! $api_key ){
			return $result;
		}

		/* Bail if wp is_email() check fail. */
		if( ! is_email( $value ) ){
			return $result;
		}

		/* API URL */
		$url = add_query_arg( array(
			'address' => urlencode( trim( $value ) ),
			'apikey'  => urlencode( trim( $api_key ) ),
		), 'https://bpi.briteverify.com/emails.json' );

		/* Request Check */
		$raw_response = wp_remote_get( esc_url_raw( $url ) );

		/* Request to BriteVerify fail */
		if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) ) {
			$result['is_valid'] = false;
			$result['message'] = __( 'Unable to validate email. Email validation request error. Please try again or contact administrator.', 'briteverify-gravityforms' );
			return $result;
		}

		/* JSON Data Result */
		$data = json_decode( trim( wp_remote_retrieve_body( $raw_response ) ), true );

		/* Check status */
		if( isset( $data['status'] ) ){

			/* Email is valid */
			if( 'valid' == $data['status'] ){
				$result['is_valid'] = true;
				$result['message'] = '';

				/* If do not allow disposable and email is disposable, return error */
				if( ! $allow_dp && isset( $data['disposable'] ) && true == $data['disposable'] ){
					$result['is_valid'] = false;
					$result['message'] = __( 'Please use your real email address. You are not allowed to use disposable email in this form.', 'briteverify-gravityforms' );
				}
			}
			/* Email not valid */
			else{
				$result['is_valid'] = false;
				$result['message'] = __( 'Email is not valid. Please check your email input.', 'briteverify-gravityforms' );
			}
		}

		/* No status data found (invalid return value) */
		else{
			$result['is_valid'] = false;
			$result['message'] = __( 'Unable to validate email. Invalid validation API results. Please try again or contact administrator.', 'briteverify-gravityforms' );
		}
		return $result;
	}

}
