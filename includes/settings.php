<?php
/**
 * GravityForms Settings
 * @since 1.0.0
**/
if ( ! defined( 'WPINC' ) ) { die; }

/* Load Class */
BV_GF_Settings::get_instance();

/**
 * Settings Page
 * @since 1.0.0
 */
class BV_GF_Settings{

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

		/* Create Settings Menu (Tab) */
		add_filter( 'gform_settings_menu', array( $this, 'add_settings_tab' ) );

		/* Add settings page content */
		add_action( 'gform_settings_bv_gf', array( $this, 'settings' ) );

		/* Add tooltips */
		add_filter( 'gform_tooltips', array( $this, 'add_tooltips' ) );
	}


	/**
	 * Add Settings Tab in GF Settings
	 * @since 1.0.0
	 * @link https://www.gravityhelp.com/documentation/article/gform_settings_menu/
	 */
	public function add_settings_tab( $tabs ) {
		$tabs[] = array(
			'name'   => 'bv_gf',
			'label'  => 'BriteVerify',
		);
		return $tabs;
	}


	/**
	 * Settings Content
	 * @since 1.0.0
	 */
	public function settings(){

		/* Submit Settings */
		if ( isset( $_POST['submit'], $_POST['gforms_update_settings'] ) ) {

			/* Check admin referer/Nonce */
			check_admin_referer( 'gforms_update_settings', 'gforms_update_settings' );

			/* Check user permission */
			if ( ! GFCommon::current_user_can_any( 'gravityforms_edit_settings' ) ) {
				die( esc_html__( "You don't have adequate permission to edit settings.", 'briteverify-gravityforms' ) );
			}

			/* === Save Settings === */

			/* API Key */
			if( isset( $_POST['bv_gf_api_key'] ) ){
				if( $_POST['bv_gf_api_key'] ){

					/* API Key */
					$api_key = esc_html( trim( $_POST['bv_gf_api_key'] ) );
					$api_key_option = esc_html( trim( get_option( 'bv_gf_api_key' ) ) );

					/* Check Key: Only if it's updated (new value) */
					if( $api_key != $api_key_option ){

						$url = add_query_arg( array(
							'address' => urlencode( trim( get_option( 'admin_email' ) ) ),
							'apikey'  => urlencode( trim( $api_key ) ),
						), 'https://bpi.briteverify.com/emails.json' );
						$raw_response = wp_remote_get( esc_url_raw( $url ) );
						if ( ! is_wp_error( $raw_response ) && 200 == wp_remote_retrieve_response_code( $raw_response ) ) {
							$data = json_decode( trim( wp_remote_retrieve_body( $raw_response ) ), true );
							if( isset( $data['status'] ) ){
								?>
								<div class="updated fade" style="padding:6px;">
									<?php esc_html_e( 'Your BriteVerify API Key is valid.', 'briteverify-gravityforms' ); ?>
								</div>
								<?php
							}
							else{
								?>
								<div class="error fade" style="padding:6px;">
									<?php esc_html_e( 'Your BriteVerify API Key is not valid.', 'briteverify-gravityforms' ); ?>
								</div>
								<?php
							}
						}
						elseif( 401 == wp_remote_retrieve_response_code( $raw_response ) ){
							?>
							<div class="error fade" style="padding:6px;">
								<?php esc_html_e( 'Your BriteVerify API Key is not valid.', 'briteverify-gravityforms' ); ?>
							</div>
							<?php
						}

						/* Update option with new API Key */
						update_option( 'bv_gf_api_key', esc_html( trim( $api_key ) ) );

					} // end check updated.
				}
				else{
					delete_option( 'bv_gf_api_key' );
				}
			}

			/* Default: Enable */
			if( isset( $_POST['bv_gf_enable'] ) ){
				update_option( 'bv_gf_enable', 1 );
			}
			else{
				delete_option( 'bv_gf_enable' );
			}

			/* Default: Allow Disposable */
			if( isset( $_POST['bv_gf_allow_disposable'] ) ){
				update_option( 'bv_gf_allow_disposable', 1 );
			}
			else{
				delete_option( 'bv_gf_allow_disposable' );
			}

			/* Admin Notice */
			?>
			<div class="updated fade" style="padding:6px;">
				<?php esc_html_e( 'Settings Updated.', 'briteverify-gravityforms' ); ?>
			</div>
			<?php
		}

		/* Settings Page */
		?>
		<form id="gforms_settings" method="post">
			<?php wp_nonce_field( 'gforms_update_settings', 'gforms_update_settings' ) ?>

			<h3><span><i class="fa fa-envelope-o"></i> <?php _e( 'BriteVerify', 'briteverify-gravityforms' ); ?></span></h3>

			<p style="text-align: left;">
				<?php _e( 'BriteVerify is an email verification service to make sure all email field submission  in your Gravity Forms is valid. <a href="http://www.briteverify.com/" target="_blank">Read more about BriteVerify</a>.', 'briteverify-gravityforms' ); ?>
			</p>

			<table class="form-table">

				<tr valign="top">
					<th scope="row">
						<label for="bv_gf_api_key"><?php esc_html_e( 'API Key', 'briteverify-gravityforms' ); ?></label>
					</th>
					<td>
						<input autocomplete="off" type="text" name="bv_gf_api_key" id="bv_gf_api_key" style="width:350px;" value="<?php echo sanitize_text_field( esc_html( get_option( 'bv_gf_api_key' ) ) ); ?>" />
						<br />
						<span class="gf_settings_description">
							<?php esc_html_e( 'API Key to connect to BriteVerify Real-Time API.', 'briteverify-gravityforms' ); ?>
						</span>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<?php esc_html_e( 'Default Options', 'briteverify-gravityforms' ); ?>
					</th>
					<td>
						<label>
							<input autocomplete="off" type="checkbox" name="bv_gf_enable" id="bv_gf_enable" value="1" <?php checked( 1, get_option( 'bv_gf_enable' ) ); ?>> <?php esc_html_e( 'Enable in all email fields.', 'briteverify-gravityforms' ); ?> <?php gform_tooltip( 'bv_gf_enable' ) ?>
						</label>
						<br />
						<label>
							<input autocomplete="off" type="checkbox" name="bv_gf_allow_disposable" id="bv_gf_allow_disposable" value="1" <?php checked( 1, get_option( 'bv_gf_allow_disposable' ) ); ?>> <?php esc_html_e( 'Allow disposable email.', 'briteverify-gravityforms' ); ?> <?php gform_tooltip( 'bv_gf_allow_disposable' ) ?>
						</label>
					</td>
				</tr>

			</table>

			<p class="submit" style="text-align:left;">
				<input name="submit" value="<?php esc_html_e( 'Save Settings', 'briteverify-gravityforms' ); ?>" class="button-primary gfbutton" id="save" type="submit">
			</p>

		</form>
		<?php
	}

	/**
	 * Add tooltips
	 * @since 1.0.0
	 */
	public function add_tooltips( $tooltips ){

		/* Default: Enable */
		$tooltips['bv_gf_enable'] = '<h6>' . esc_html__( 'Enable In All Email Fields', 'briteverify-gravityforms' ) . '</h6>' . esc_html__( 'This is the default option. You can still disable/enable this feature in each email field using advanced field settings.', 'briteverify-gravityforms' );

		/* Default: Allow Disposable */
		$tooltips['bv_gf_allow_disposable'] = '<h6>' . esc_html__( 'Allow Disposable Email', 'briteverify-gravityforms' ) . '</h6>' . esc_html__( 'This is the default option. You can still disable/enable this feature in each email field using advanced field settings.', 'briteverify-gravityforms' );

		return $tooltips;
	}

}
