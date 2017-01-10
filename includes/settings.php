<?php
/**
 * Additional GF Settings
 * @since 1.0.0
**/

/* Create Settings Menu (Tab) */
add_filter( 'gform_settings_menu', 'bv_gf_add_settings_tab' );

/**
 * Add Settings Tab in GF Settings
 * @since 1.0.0
 * @link https://www.gravityhelp.com/documentation/article/gform_settings_menu/
 */
function bv_gf_add_settings_tab( $tabs ) {
	$tabs[] = array(
		'name'   => 'bv_gf',
		'label'  => 'BriteVerify',
	);
	return $tabs;
}

/* Add settings page */
add_action( 'gform_settings_bv_gf', 'bv_gf_settings' );

/**
 * Settings Content
 * @since 1.0.0
 */
function bv_gf_settings(){
	?>
	<form id="gforms_settings" method="post">
		<?php wp_nonce_field( 'gforms_update_settings', 'gforms_update_settings' ) ?>

		<h3><span><i class="fa fa-cogs"></i> <?php _e( 'BriteVerify', 'briteverify-gravityforms' ); ?></span></h3>

		<p style="text-align: left;">
			<?php _e( 'BriteVerify is an email verification service to make sure all email input in your Gravity Forms is valid. <a href="http://www.briteverify.com/" target="_blank">Read more about BriteVerify</a>.', 'briteverify-gravityforms' ); ?>
		</p>

		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="bv_gf_api_key"><?php esc_html_e( 'API Key', 'briteverify-gravityforms' ); ?></label>  <?php gform_tooltip( 'settings_license_key' ) ?>
				</th>
				<td>
					<input type="password" name="bv_gf_api_key" id="bv_gf_api_key" style="width:350px;" value="" />
					<br />
					<span class="gf_settings_description"><?php esc_html_e( 'The license key is used for access to automatic upgrades and support.', 'gravityforms' ); ?></span>
				</td>
			</tr>
		</table>
	
	</form>
	<?php
}



















