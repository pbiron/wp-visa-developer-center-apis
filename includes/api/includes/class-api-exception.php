<?php

namespace SHC\VDC;

use Exception;

/**
 * ApiException
 *
 * Based on VDC Sample Code.
 *
 * @since 0.1.0
 */
class ApiException extends Exception {

	/**
	 * The HTTP body of the server response either as Json or string.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $responseBody;

	/**
	 * The HTTP header of the server response.
	 *
	 * @since 0.1.0
	 *
	 * @var string[]
	 */
	protected $responseHeaders;

	/**
	 * Constructor
	 *
	 * @param string $message         Error message
	 * @param int    $code            HTTP status code
	 * @param string $responseHeaders HTTP response header
	 * @param mixed  $responseBody    HTTP body of the server response either as Json or string
	 */
	public function __construct( $message = "", $code = 0, $responseHeaders = null, $responseBody = null ) {
		parent::__construct( $message, $code );

		$this->responseHeaders = $responseHeaders;
		$this->responseBody    = $responseBody;
	}

	/**
	 * Gets the HTTP response header.
	 *
	 * @return string HTTP response header
	 */
	public function getResponseHeaders() {
		return $this->responseHeaders;
	}

	/**
	 * Gets the HTTP body of the server response either as Json or string
	 *
	 * @return mixed HTTP body of the server response either as Json or string
	 */
	public function getResponseBody() {
		return $this->responseBody;
	}
}
