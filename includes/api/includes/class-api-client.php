<?php

namespace SHC\VDC;

/**
 * Class to perform a VDC API request and get the response.
 *
 * Based on VDC Sample Code.
 *
 * @since 0.1.0
 *
 * @todo eventually, scrap this entire class and just use wp_remote_request(),
 *       but will have to figure out how to do the Two-Way SSL with wp_remote_request(),
 *       and don't have time for that at the moment.
 */
class API_Client {
	public static $HEAD    = 'HEAD';
	public static $GET     = 'GET';
	public static $POST    = 'POST';
	public static $PUT     = 'PUT';
	public static $PATCH   = 'PATCH';
	public static $DELETE  = 'DELETE';
	public static $OPTIONS = 'OPTIONS';

	/**
	 * Configuration.
	 *
	 * @since 0.1.0
	 *
	 * @var Configuration
	 */
	protected $config;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param Configuration $config config for this ApiClient.
	 */
	public function __construct( $config = null ) {
		if ( ! $config ) {
			$config = Configuration::getDefaultConfiguration();
		}

		$this->config = $config;
	}

	/**
	 * Get the config.
	 *
	 * @since 0.1.0
	 *
	 * @return Configuration
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * Get API key (with prefix if set).
	 *
	 * @since 0.1.0
	 *
	 * @param  string $apiKeyIdentifier name of apikey
	 * @param  array $queryParams key value pair of name and apikeys
	 * @param  string $resourcePath resource path of the endpoint
	 * @param  object $body http request body
	 * @return string API key with the prefix
	 */
	public function getApiKeyWithPrefix( $apiKeyIdentifier,  $queryParams, $resourcePath, $body ) {
		$prefix = $this->config->getApiKeyPrefix( $apiKeyIdentifier );
		$apiKey = $this->config->getApiKey( $apiKeyIdentifier, $queryParams, $resourcePath, $body );

		if ( ! isset( $apiKey ) ) {
			return null;
		}

		if ( isset( $prefix ) ) {
			$keyWithPrefix = "{$prefix} {$apiKey}";
		}
		else {
			$keyWithPrefix = $apiKey;
		}

		return $keyWithPrefix;
	}

	/**
	 * Make the HTTP call (Sync)
	 *
	 * @since 0.1.0
	 *
	 * @param string $path path to method endpoint
	 * @param string $resourcePath resourcePath of the endpoint
	 * @param string $method       method to call
	 * @param array  $queryParams  parameters to be place in query URL
	 * @param array  $postData     parameters to be placed in POST body
	 * @param array  $headerParams parameters to be place in request header
	 * @param string $responseType expected response type of the endpoint
	 * @param string $endpointPath path to method endpoint before expanding parameters
	 * @throws ApiException on non-2xx response
	 * @return array {
	 *     @type xxx $result
	 *     @type string $http_status_code
	 *     @type string[] $http_response_headers
	 * }
	 */
	public function callApi( $path, $resourcePath, $method, $queryParams = array(), $postData = array(), $headerParams = array(), $responseType = null, $endpointPath = null ) {
		$headers = array();

		$_header_accept = $this->selectHeaderAccept( array( 'application/json' ) );
		if (!is_null($_header_accept)) {
			$headerParams['Accept'] = $_header_accept;
		}
		$headerParams['Content-Type'] = $this->selectHeaderContentType( array( 'application/json' ) );

		// this endpoint requires HTTP basic authentication
		if ( ! empty( $this->getConfig()->getUsername() ) || ! empty( $this->getConfig()->getPassword() ) ) {
			$headerParams['Authorization'] = sprintf(
				'Basic %s',
				base64_encode(
					sprintf(
						'%s:%s',
						$this->getConfig()->getUsername(),
						$this->getConfig()->getPassword()
					)
				)
			);
		}

		// construct the http header
		$headerParams = array_merge(
			(array) $this->config->getDefaultHeaders(),
			(array) $headerParams
		);

		foreach ( $headerParams as $key => $val ) {
			$headers[] = "$key: $val";
		}

		if ( empty( $queryParams ) ) {
			$queryParams = array();
		}
		$queryParams = array_filter( $queryParams );

		if ( count( $queryParams ) ) {
			ksort( $queryParams );
		}

		// form data
		if ( $postData && in_array( 'Content-Type: application/x-www-form-urlencoded', $headers, true ) ) {
			$postData = http_build_query( $postData );
		}
		elseif ( ( is_object( $postData ) || is_array( $postData ) ) && ! in_array( 'Content-Type: multipart/form-data', $headers, true ) ) { // json model
			$postData = array_filter( $postData );
			$postData = json_encode( $postData );
		}

		$url = $this->config->getHost() . $path;

		$curl = curl_init();
		// set timeout, if needed
		if ( $this->config->getCurlTimeout() !== 0 ) {
			curl_setopt( $curl, CURLOPT_TIMEOUT, $this->config->getCurlTimeout() );
		}
		// set connect timeout, if needed
		if ( $this->config->getCurlConnectTimeout() !== 0 ) {
			curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, $this->config->getCurlConnectTimeout() );
		}

		// return the result on success, rather than just true
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );

		// disable SSL verification, if needed
		if ( $this->config->getSSLVerification() === false ) {
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		}

		if ( $this->config->getCurlProxyHost() ) {
			curl_setopt( $curl, CURLOPT_PROXY, $this->config->getCurlProxyHost() );
		}

		if ( $this->config->getCurlProxyPort() ) {
			curl_setopt( $curl, CURLOPT_PROXYPORT, $this->config->getCurlProxyPort() );
		}

		if ( $this->config->getCertificatePath() ) {
			curl_setopt( $curl, CURLOPT_SSLCERT, $this->config->getCertificatePath() );
		}

		if ( $this->config->getPrivateKey() ) {
			curl_setopt( $curl, CURLOPT_SSLKEY, $this->config->getPrivateKey() );
		}

		if ( $this->config->getCaCertPath() ) {
			curl_setopt( $curl, CURLOPT_CAINFO, $this->config->getCaCertPath() );
		}

		if ( $this->config->getCurlProxyType() ) {
			curl_setopt( $curl, CURLOPT_PROXYTYPE, $this->config->getCurlProxyType() );
		}

		if ( $this->config->getCurlProxyUser() ) {
			curl_setopt( $curl, CURLOPT_PROXYUSERPWD, $this->config->getCurlProxyUser() . ':' .$this->config->getCurlProxyPassword() );
		}

		if ( ! empty( $queryParams ) ) {
			$url = $url . '?' . http_build_query( $queryParams );
		}

		switch ( $method ) {
			case self::$HEAD:
				curl_setopt($curl, CURLOPT_NOBODY, true);

				break;
			case self::$GET:
				break;
			case self::$POST:
				curl_setopt( $curl, CURLOPT_POST, true );
				curl_setopt( $curl, CURLOPT_POSTFIELDS, $postData );

				break;
			case self::$PATCH:
				curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'PATCH' );
				curl_setopt( $curl, CURLOPT_POSTFIELDS, $postData );

				break;
			case self::$PUT:
				curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'PUT' );
				curl_setopt( $curl, CURLOPT_POSTFIELDS, $postData );

				break;
			case self::$DELETE:
				curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'DELETE' );
				curl_setopt( $curl, CURLOPT_POSTFIELDS, $postData );

				break;
			case self::$OPTIONS:
				curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'OPTIONS' );
				curl_setopt( $curl, CURLOPT_POSTFIELDS, $postData );

				break;
			default:
				throw new ApiException( sprintf( __( 'curl method %s is not recognized.', 'wp-visa-developer-center-apis' ), $method ) );
		}

		curl_setopt( $curl, CURLOPT_URL, $url );

		// Set user agent
		curl_setopt( $curl, CURLOPT_USERAGENT, $this->config->getUserAgent() );

		// debugging for curl
		if ( $this->config->getDebug() ) {
			error_log( "[DEBUG] HTTP Request body  ~BEGIN~" . PHP_EOL . print_r( $postData, true ) . PHP_EOL . "~END~" . PHP_EOL, 3, $this->config->getDebugFile() );

			curl_setopt( $curl, CURLOPT_VERBOSE, 1 );
			curl_setopt($curl, CURLOPT_STDERR, fopen($this->config->getDebugFile(), 'a'));
//            curl_setopt( $curl, CURLOPT_STDERR, STDOUT );
		}
		else {
			curl_setopt( $curl, CURLOPT_VERBOSE, 0 );
		}

		// obtain the HTTP response headers
		curl_setopt( $curl, CURLOPT_HEADER, 1 );

		// Make the request
		$response         = curl_exec( $curl );
		$http_header_size = curl_getinfo( $curl, CURLINFO_HEADER_SIZE );
		$http_header      = $this->httpParseHeaders( substr( $response, 0, $http_header_size ) );
		$http_body        = substr( $response, $http_header_size );
		$response_info    = curl_getinfo( $curl );

		// debug HTTP response body
		if ( $this->config->getDebug() ) {
			error_log( "[DEBUG] HTTP Response body ~BEGIN~" . PHP_EOL . print_r( $http_body, true ) . PHP_EOL . "~END~" . PHP_EOL, 3, $this->config->getDebugFile() );
		}

		// Handle the response
		if ( $response_info['http_code'] >= 200 && $response_info['http_code'] <= 299 ) {
			$data = $http_body;
		}
		elseif ( 0 === $response_info['http_code'] ) {
			$curl_error_message = curl_error( $curl );

			// curl_exec can sometimes fail but still return a blank message from curl_error().
			if ( ! empty( $curl_error_message ) ) {
				/* translators: 1: URL, 2: Error message */
				$error_message = sprintf( __( 'API call to %1$s failed: %2$s', 'wp-visa-developer-center-apis' ), $url, $curl_error_message );
			}
			else {
				$error_message = sprintf(
					__(
						/* translators: 1 URL */
						'API call to %1$s failed, but for an unknown reason.' .
							'This could happen if you are disconnected from the network.',
						'wp-visa-developer-center-apis'
					),
					$url
				);
			}

			$exception = new ApiException( $error_message, 0, null, null );

			throw $exception;
		}
		else {
			$data = json_decode( $http_body );
			if ( json_last_error() > 0 ) { // if response is a string
				$data = $http_body;
			}

			throw new ApiException(
				/* translators: 1: HTTP response code, 2: URL */
				sprintf( __( '[%1$s] Error connecting to the API (%2$s)', 'wp-visa-developer-center-apis' ), $response_info['http_code'], $url ),
				$response_info['http_code'],
				$http_header,
				$data
			);
		}

		return array( $data, $response_info['http_code'], $http_header );
	}

	/**
	 * Return the header 'Accept' based on an array of Accept provided
	 *
	 * @since 0.1.0
	 *
	 * @param string[] $accept Array of header
	 * @return string Accept (e.g. application/json)
	 */
	public function selectHeaderAccept( $accept ) {
		if ( empty( $accept ) || ( count( $accept ) === 1 && empty( $accept[0] ) ) ) {
			return null;
		}
		elseif ( preg_grep( '/application\/json/i', $accept ) ) {
			return 'application/json';
		}
		else {
			return implode( ',', $accept );
		}
	}

	/**
	 * Return the content type based on an array of content-type provided
	 *
	 * @since 0.1.0
	 *
	 * @param string[] $content_type Array fo content-type
	 * @return string Content-Type (e.g. application/json)
	 */
	public function selectHeaderContentType( $content_type ) {
		if ( empty( $content_type ) || ( count( $content_type ) === 1 && empty( $content_type[0] ) ) ) {
			return 'application/json';
		}
		elseif ( preg_grep( '/application\/json/i', $content_type ) ) {
			return 'application/json';
		}
		else {
			return implode( ',', $content_type );
		}
	}

	/**
	 * Return an array of HTTP response headers
	 *
	 * @since 0.1.0
	 *
	 * @param string $raw_headers A string of raw HTTP response headers
	 * @return string[] Array of HTTP response heaers
	 */
	protected function httpParseHeaders( $raw_headers ) {
		// ref/credit: http://php.net/manual/en/function.http-parse-headers.php#112986
		$headers = [];
		$key = '';

		foreach ( explode( "\n", $raw_headers ) as $h ) {
			$h = explode( ':', $h, 2 );

			if ( isset( $h[1] ) ) {
				if ( ! isset( $headers[$h[0]] ) ) {
					$headers[ $h[0] ] = trim( $h[1] );
				}
				elseif ( is_array( $headers[ $h[0] ] ) ) {
					$headers[ $h[0] ] = array_merge( $headers[ $h[0] ], array( trim( $h[1 ] ) ) );
				}
				else {
					$headers[ $h[0] ] = array_merge( array( $headers[ $h[0] ] ), array( trim( $h[1] ) ) );
				}

				$key = $h[0];
			}
			else {
				if ( substr( $h[0], 0, 1 ) === "\t" ) {
					$headers[ $key ] .= "\r\n\t" . trim( $h[0] );
				}
				elseif ( ! $key ) {
					$headers[0] = trim( $h[0] );
				}
				trim( $h[0] );
			}
		}

		return $headers;
	}
}
