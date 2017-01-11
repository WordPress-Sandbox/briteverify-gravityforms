<?php
/**
 * Email Field Options
 * @since 1.0.0
**/
if ( ! defined( 'WPINC' ) ) { die; }

/* Load Class */
BV_GF_Field_Settings::get_instance();

/**
 * Email Field Options
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

		/* Advance Tab */
		add_action( 'gform_field_advanced_settings', array( $this, 'advanced_settings' ), 10, 2 );

		/* JS to show the field */
		add_action( 'gform_editor_js', array( $this, 'field_editor_js' ) );

	}

	/**
	 * Advanced Settings
	 * @since 1.0.0
	 */
	public function advanced_settings( $position, $form_id ){
		if( 400 === $position ){
			?>
			<li class="bv_gf_enable_field_setting field_setting">
				<label class="section_label"><?php esc_html_e( 'BriteVerify: Enable Verification', 'briteverify-gravityforms' ); ?> </label>
				<p><span></p>
				<div>

					<input autocomplete="off" type="radio" name="bv_gf_enable" id="bv_gf_enable_default" size="10" value="" onclick="return SetFieldProperty( 'bv_gf_enable', this.value );" onkeypress="return SetFieldProperty( 'bv_gf_enable', this.value );" />
					<label for="bv_gf_enable_default" class="inline">
						<?php printf( esc_html__( 'Default (%s)', 'briteverify-gravityforms' ), 1 == get_option( 'bv_gf_enable' ) ? __( 'Enabled', 'briteverify-gravityforms' ) : __( 'Disabled', 'briteverify-gravityforms' ) ); ?>
					</label>
					&nbsp;&nbsp;

					<input autocomplete="off" type="radio" name="bv_gf_enable" id="bv_gf_enable_yes" size="10" value="yes" onclick="return SetFieldProperty( 'bv_gf_enable', this.value );" onkeypress="return SetFieldProperty( 'bv_gf_enable', this.value );"/>
					<label for="bv_gf_enable_yes" class="inline">
						<?php esc_html_e( 'Enable', 'briteverify-gravityforms' ); ?>
					</label>
					&nbsp;&nbsp;

					<input autocomplete="off" type="radio" name="bv_gf_enable" id="bv_gf_enable_no" size="10" value="no" onclick="return SetFieldProperty( 'bv_gf_enable', this.value );" onkeypress="return SetFieldProperty( 'bv_gf_enable', this.value );"/>
					<label for="bv_gf_enable_no" class="inline">
						<?php esc_html_e( 'Disable', 'briteverify-gravityforms' ); ?>
					</label>

				</div>
				<br class="clear" />
			</li>
			<li class="bv_gf_allow_disposable_field_setting field_setting">
				<label class="section_label"><?php esc_html_e( 'BriteVerify: Allow Disposable Email', 'briteverify-gravityforms' ); ?> </label>
				<div>

					<input autocomplete="off" type="radio" name="bv_gf_allow_disposable" id="bv_gf_allow_disposable_default" size="10" value="" onclick="return SetFieldProperty( 'bv_gf_allow_disposable', this.value );" onkeypress="return SetFieldProperty( 'bv_gf_allow_disposable', this.value );"/>
					<label for="bv_gf_allow_disposable_default" class="inline">
						<?php printf( esc_html__( 'Default (%s)', 'briteverify-gravityforms' ), 1 == get_option( 'bv_gf_allow_disposable' ) ? __( 'Allow', 'briteverify-gravityforms' ) : __( 'Do Not Allow', 'briteverify-gravityforms' ) ); ?>
					</label>
					&nbsp;&nbsp;

					<input autocomplete="off" type="radio" name="bv_gf_allow_disposable" id="bv_gf_allow_disposable_yes" size="10" value="yes" onclick="return SetFieldProperty( 'bv_gf_allow_disposable', this.value );" onkeypress="return SetFieldProperty( 'bv_gf_allow_disposable', this.value );"/>
					<label for="bv_gf_allow_disposable_yes" class="inline">
						<?php esc_html_e( 'Allow', 'briteverify-gravityforms' ); ?>
					</label>
					&nbsp;&nbsp;

					<input autocomplete="off" type="radio" name="bv_gf_allow_disposable" id="bv_gf_allow_disposable_no" size="10" value="no" onclick="return SetFieldProperty( 'bv_gf_allow_disposable', this.value );" onkeypress="return SetFieldProperty( 'bv_gf_allow_disposable', this.value );"/>
					<label for="bv_gf_allow_disposable_no" class="inline">
						<?php esc_html_e( 'Do Not Allow', 'briteverify-gravityforms' ); ?>
					</label>

				</div>
				<br class="clear" />
			</li>
			<?php
		}
	}

	/**
	 * JS
	 */
	public function field_editor_js(){
		?>
		<script type='text/javascript'>
			/* Display setting only on "email" field type */
			fieldSettings["email"] += ", .bv_gf_enable_field_setting, .bv_gf_allow_disposable_field_setting";

			/* Bind on "load field" to display saved data */
			jQuery(document).bind("gform_load_field_settings", function( event, field, form ){
				if( 'email' != field.type ){
					return;
				}
				/* Enable */
				if( 'yes' == field.bv_gf_enable ){
					jQuery( '#bv_gf_enable_yes' ).prop( 'checked', true );
				}
				else if( 'no' ==  field.bv_gf_enable ){
					jQuery( '#bv_gf_enable_no' ).prop( 'checked', true );
				}
				else{
					jQuery( '#bv_gf_enable_default' ).prop( 'checked', true );
				}
				/* Allow Disposable */
				if( 'yes' == field.bv_gf_allow_disposable ){
					jQuery( '#bv_gf_allow_disposable_yes' ).prop( 'checked', true );
				}
				else if( 'no' ==  field.bv_gf_allow_disposable ){
					jQuery( '#bv_gf_allow_disposable_no' ).prop( 'checked', true );
				}
				else{
					jQuery( '#bv_gf_allow_disposable_default' ).prop( 'checked', true );
				}
			});
		</script>
		<?php
	}

}
