<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

/* Clean up stuff */
delete_option( 'bv_gf_enable' );
delete_option( 'bv_gf_allow_disposable' );