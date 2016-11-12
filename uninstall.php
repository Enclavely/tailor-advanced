<?php

/**
 * Uninstalls the Tailor Advanced Features extension.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Check user permissions
if ( ! current_user_can( 'activate_plugins' ) ) {
	return;
}