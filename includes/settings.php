<?php
/**
 * Additional GF Settings
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
		//add_filter( 'gform_tooltips', array( $this, 'add_tooltips' ) );
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
	function settings(){

		/* Submit Settings */
		if ( isset( $_POST['submit'], $_POST['bv_gf_api_key'], $_POST['gforms_update_settings'] ) ) {

			/* Check admin referer/Nonce */
			check_admin_referer( 'gforms_update_settings', 'gforms_update_settings' );

			/* Check user permission */
			if ( ! GFCommon::current_user_can_any( 'gravityforms_edit_settings' ) ) {
				die( esc_html__( "You don't have adequate permission to edit settings.", 'briteverify-gravityforms' ) );
			}

			/* Save Settings */
			if( $_POST['bv_gf_api_key'] ){
				update_option( 'bv_gf_api_key', esc_html( $_POST['bv_gf_api_key'] ) );
			}
			else{
				delete_option( 'bv_gf_api_key' );
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
				<?php _e( 'BriteVerify is an email verification service to make sure all email input in your Gravity Forms is valid. <a href="http://www.briteverify.com/" target="_blank">Read more about BriteVerify</a>.', 'briteverify-gravityforms' ); ?>
			</p>

			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="bv_gf_api_key"><?php esc_html_e( 'API Key', 'briteverify-gravityforms' ); ?></label>  <?php //gform_tooltip( 'bv_gf_api_key' ) ?>
					</th>
					<td>
						<input type="password" name="bv_gf_api_key" id="bv_gf_api_key" style="width:350px;" value="<?php echo sanitize_text_field( esc_html( get_option( 'bv_gf_api_key' ) ) ); ?>" />
						<br />
						<span class="gf_settings_description"><?php esc_html_e( 'API Key to connect to BriteVerify Real-Time API.', 'briteverify-gravityforms' ); ?></span>
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
	function add_tooltips( $tooltips ){

		$tooltips['bv_gf_api_key'] = '<h6>' . esc_html__( 'BriteVerify API Key', 'briteverify-gravityforms' ) . '</h6>' . esc_html__( '....', 'briteverify-gravityforms' );
		return $tooltips;
	}


}



