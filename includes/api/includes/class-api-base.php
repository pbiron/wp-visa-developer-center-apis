<?php

namespace SHC\VDC;

use InvalidArgumentException;

defined( 'ABSPATH' ) || die;

/**
 * Abstract base class for VDC APIs.
 *
 * @since 0.1.0
 */
abstract class API_Base {
	/**
	 * The namespace for our endpoints.
	 *
	 * @var string
	 */
	protected $namespace = '';

	/**
	 * API Client.
	 *
	 * @var API_Client
	 */
	protected $apiClient;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param API_Client|null $apiClient The api client to use.
	 */
	public function __construct( API_Client $apiClient = null ) {
		if ( ! $apiClient ) {
			$apiClient = new API_Client();
		}

		$this->apiClient = $apiClient;
	}

	/**
	 * Get API client
	 *
	 * @return API_Client
	 */
	public function getApiClient() {
		return $this->apiClient;
	}

	/**
	 * Set the API client
	 *
	 * @param API_Client $apiClient set the API client
	 *
	 * @return API_Base subclass.
	 */
	public function setApiClient( API_Client $apiClient ) {
		$this->apiClient = $apiClient;

		return $this;
	}

	/**
	 * Check that all required parameters for an endpoint are supplided.
	 *
	 * @since 0.1.0
	 *
	 * @param string[] $required_params Array of required parameter names
	 * @param array $params Array of supplied parameters
	 * @throws InvalidArgumentException
	 * @return bool True if all required params were supplied.
	 */
	protected function check_required_params( $required_params, $params ) {
		foreach ( $required_params as $param ) {
			if ( empty( $$params[ $param ] ) ) {
				throw new InvalidArgumentException(
					sprintf( __( 'Missing required parameter `%s`.', 'wp-visa-developer-center-apis' ), $param )
				);
			}
		}

		return true;
	}
}