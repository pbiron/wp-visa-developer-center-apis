<?php

namespace SHC\VDC;

use Exception;
use WP_CLI;
use WP_CLI_Command;
use stdClass;

defined( 'ABSPATH' ) || die;

/**
 * Abstract base class for VDC commands.
 *
 * @since 0.1.0
 */
abstract class VDC_Command extends WP_CLI_Command {
	/**
	 * The configuration instance.
	 *
	 * @since 0.1.0
	 *
	 * @var Configuration
	 */
	protected $config;

	/**
	 * The API instance.
	 *
	 * @since 0.1.0
	 *
	 * @var API_Base subclass.
	 */
	protected $api;

	/**
	 * Assoc args common to all commands.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $common_assoc_args = array(
		'vdc_debug'  => false,
		'ssl_verify' => true,
	);

	/**
	 * Mapping from our assoc_args to VDC API params.
	 *
	 * Unfortunately, WP_CLI <= 2.4.0 prohibits assoc_arg names
	 * from containing uppercase characters.  I have no idea why.
	 *
	 * @since 0.1.0
	 *
	 * @var string[]
	 *
	 * @todo open an issue against WP_CLI to fix that.
	 */
	protected $assoc_args_mapping = array();

	/**
	 * Call an API method (make a request against an endpoint).
	 *
	 * @since 0.1.0
	 *
	 * @param string $method The method (endpoint) to call.
	 * @param array $args    The query and/or post data for the call.
	 * @return void
	 */
	protected function call_api( $method, $args ) {
		if ( ! method_exists( $this->api, $method ) ) {
			WP_CLI::error( sprintf( 'Method %s::%s() not defined.', get_class( $this->api ), $method ) );
		}

		try {
			list( $result, $statusCode, $httpHeader ) = $this->api->{$method}( $args );

			// printy print the json.  this is inefficient (decoding/reencoding), but it's
			// just for display purposes, so no big deal.
			$result = json_encode( json_decode( $result ), JSON_PRETTY_PRINT );

			WP_CLI::line( 'Response: ' . $result );
		}
		catch ( ApiException $ex ) {
			WP_CLI::error( $ex->getMessage(), false );
			$responseBody = json_encode( $ex->getResponseBody(), JSON_PRETTY_PRINT );

			WP_CLI::error( $responseBody );
		}
		catch ( Exception $ex ) {
			WP_CLI::error( $ex->getMessage() );
		}

		return;
	}

	/**
	 * Set up the VDC configuration
	 *
	 * @since 0.1.0
	 *
	 * @return void.
	 */
	protected function setup_config( $args ) {
		$config = $this->api->getApiClient()->getConfig();

		$config->setSSLVerification( $args['ssl_verify'] );
		$config->setDebug( $args['vdc_debug'] );

		return;
	}

	/**
	 * Map assoc_args to VDC queryParams or postData names.
	 *
	 * Unfortunately, WP_CLI <= 2.4.0 prohibits assoc_arg names
	 * from containing uppercase characters.  I have no idea why.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function map_params( $args ) {
		$params = array();

		foreach ( $args as $name => $value ) {
			if ( isset( $this->assoc_args_mapping[ $name ] ) ) {
				$params[ $this->assoc_args_mapping[ $name ] ] = $value;
			}
			else {
				$params[ $name ] = $value;
			}
		}

		return $params;
	}
}
