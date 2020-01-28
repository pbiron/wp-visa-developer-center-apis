<?php

/**
 * Plugin Name: Visa Developer Center APIs
 * Description: Access Visa Developer Center APIs
 * Version: 0.1.0
 * Author: Paul V. Biron/Sparrow Hawk Computing
 * Author URI: https://sparrowhawkcomputing.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace SHC\VDC;

use WP_CLI;

require_once __DIR__ . '/vendor/autoload.php';

add_action(
	'plugins_loaded',
	/**
	 * Ensure the files in the includes/certs directory are sufficiently protected.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function() {
		$htaccess = __DIR__ . '/includes/certs/.htaccess';
		if ( ! file_exists( $htaccess ) ) {
			$content =<<<EOF
<IfModule mod_authz_core.c>
	Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
	Order deny,allow
	Deny from all
</IfModule>

EOF;
			file_put_contents( $htaccess, $content );
		}

		$index = __DIR__ . '/includes/certs/index.php';
		if ( ! file_exists( $index ) ) {
			$content =<<<EOF
<?php
//silence is golden

EOF;
			file_put_contents( $index, $content );
		}

		return;
	}
);

add_action(
	'cli_init',
	/**
	 * Add our WP_CLI commands.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function() {
		WP_CLI::add_command( 'vdc eligibility', __NAMESPACE__ . '\\Eligibility_Command' );
		WP_CLI::add_command( 'vdc offers',      __NAMESPACE__ . '\\Offers_Command' );
		WP_CLI::add_command( 'vdc data',        __NAMESPACE__ . '\\Data_Command' );

		return;
	}
);
