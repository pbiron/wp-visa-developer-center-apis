<?php

namespace SHC\VDC;

use InvalidArgumentException;

/**
 * Configuration
 *
 * Based on VDC Sample Code.
 *
 * @since 0.1.0
 */
class Configuration {
	private static $defaultConfiguration = null;

	/**
	 * Associate array to store API key(s)
	 *
	 * @var string[]
	 */
	protected $apiKeys = array();

	/**
	 * Associate array to store API prefix (e.g. Bearer)
	 *
	 * @var string[]
	 */
	protected $apiKeyPrefixes = array();

	/**
	 * Access token for OAuth
	 *
	 * @var string
	 */
	protected $accessToken = '';

	/**
	 * Username for HTTP basic authentication
	 *
	 * @var string
	 */
	protected $username = '';

	/**
	 * Password for HTTP basic authentication
	 *
	 * @var string
	 */
	protected $password = '';

	/**
	 * The default header(s)
	 *
	 * @var array
	 */
	protected $defaultHeaders = array();

	/**
	 * The host
	 *
	 * @var string
	 */
	protected $host = 'https://sandbox.api.visa.com';

	/**
	 * Timeout (second) of the HTTP request, by default set to 0, no timeout
	 *
	 * @var string
	 */
	protected $curlTimeout = 0;

	/**
	 * Timeout (second) of the HTTP connection, by default set to 0, no timeout
	 *
	 * @var string
	 */
	protected $curlConnectTimeout = 0;

	/**
	 * User agent of the HTTP request, set to "PHP-Swagger" by default
	 *
	 * @var string
	 */
	protected $userAgent = "VDP_SampleCode_Php";

	/**
	 * Debug switch (default set to false)
	 *
	 * @var bool
	 */
	protected $debug = false;

	/**
	 * Debug file location (log to STDOUT by default)
	 *
	 * @var string
	 */
	protected $debugFile = 'php://output';

	/**
	 * Debug file location (log to STDOUT by default)
	 *
	 * @var string
	 */
	protected $tempFolderPath;

	/**
	 * Indicates if SSL verification should be enabled or disabled.
	 *
	 * This is useful if the host uses a self-signed SSL certificate.
	 *
	 * @var boolean True if the certificate should be validated, false otherwise.
	 */
	protected $sslVerification = true;

	/**
	 * Curl proxy host
	 *
	 * @var string
	 */
	protected $proxyHost;

	/**
	 * Curl proxy port
	 *
	 * @var integer
	 */
	protected $proxyPort;

	/**
	 * Curl proxy type, e.g. CURLPROXY_HTTP or CURLPROXY_SOCKS5
	 *
	 * @see https://secure.php.net/manual/en/function.curl-setopt.php
	 * @var integer
	 */
	protected $proxyType;

	/**
	 * Curl proxy username
	 *
	 * @var string
	 */
	protected $proxyUser;

	/**
	 * Curl proxy password
	 *
	 * @var string
	 */
	protected $proxyPassword;

	protected $certificatePath;
	protected $privateKey;
	protected $caCertPath;

	protected $sharedSecret;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		$this->tempFolderPath = sys_get_temp_dir();

		$this->setUsername( Project_Config::getUsername() );
		$this->setPassword( Project_Config::getPassword() );
		$this->setCertificatePath( Project_Config::getCertificatePath() );
		$this->setPrivateKey( Project_Config::getPrivateKeyPath() );
		$this->setCaCertPath( Project_Config::getCaCertPath() );

		$this->setSSLVerification( false );
	}

	/**
	 * Sets API key
	 *
	 * @since 0.1.0
	 *
	 * @param string $apiKeyIdentifier API key identifier (authentication scheme)
	 * @param string $key              API key or token
	 *
	 * @return Configuration
	 */
	public function setApiKey( $apiKeyIdentifier, $key ) {
		$this->apiKeys[ $apiKeyIdentifier ] = $key;

		return $this;
	}

	/**
	 * Gets API key
	 *
	 * @since 0.1.0
	 *
	 * @param string $apiKeyIdentifier API key identifier (authentication scheme)
	 *
	 * @return string API key or token
	 */
	public function getApiKey( $apiKeyIdentifier, $queryParams, $resourcePath, $body ) {
		if ( 'x-pay-token' === $apiKeyIdentifier ){
			return $this->getXpayToken( $queryParams, $resourcePath, $body );
		}

		return isset( $this->apiKeys[ $apiKeyIdentifier ] ) ? $this->apiKeys[ $apiKeyIdentifier ] : null;
	}

	public function getXpayToken( $queryParams, $resourcePath, $body ) {
		$sharedSecret = $this->sharedSecret;
		$time         = time();
		ksort( $queryParams );
		$queryString = http_build_query( $queryParams );

		if ( is_object( $body ) || is_array( $body ) ) {
			$body = json_encode( $body );
		}

		$requestBodyString = $body == null ? '' : $body;
		$preHashString     = $time . $resourcePath . $queryString . $requestBodyString;
		$xPayToken         = "xv2:" . $time . ":" . hash_hmac( 'sha256', $preHashString, $sharedSecret );

		return $xPayToken;
	}
	/**
	 * Sets the prefix for API key (e.g. Bearer)
	 *
	 * @param string $apiKeyIdentifier API key identifier (authentication scheme)
	 * @param string $prefix           API key prefix, e.g. Bearer
	 *
	 * @return Configuration
	 */
	public function setApiKeyPrefix( $apiKeyIdentifier, $prefix ) {
		$this->apiKeyPrefixes[ $apiKeyIdentifier ] = $prefix;

		return $this;
	}

	/**
	 * Gets API key prefix
	 *
	 * @param string $apiKeyIdentifier API key identifier (authentication scheme)
	 *
	 * @return string
	 */
	public function getApiKeyPrefix( $apiKeyIdentifier ) {
		return isset( $this->apiKeyPrefixes[ $apiKeyIdentifier ] ) ? $this->apiKeyPrefixes[ $apiKeyIdentifier ] : null;
	}

	/**
	 * Sets the access token for OAuth
	 *
	 * @param string $accessToken Token for OAuth
	 *
	 * @return Configuration
	 */
	public function setAccessToken( $accessToken ) {
		$this->accessToken = $accessToken;

		return $this;
	}

	/**
	 * Gets the access token for OAuth
	 *
	 * @return string Access token for OAuth
	 */
	public function getAccessToken() {
		return $this->accessToken;
	}

	/**
	 * Sets the username for HTTP basic authentication
	 *
	 * @param string $username Username for HTTP basic authentication
	 *
	 * @return Configuration
	 */
	public function setUsername( $username ) {
		$this->username = $username;

		return $this;
	}

	/**
	 * Gets the username for HTTP basic authentication
	 *
	 * @return string Username for HTTP basic authentication
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Sets the password for HTTP basic authentication
	 *
	 * @param string $password Password for HTTP basic authentication
	 *
	 * @return Configuration
	 */
	public function setPassword( $password ) {
		$this->password = $password;

		return $this;
	}

	/**
	 * Gets the password for HTTP basic authentication
	 *
	 * @return string Password for HTTP basic authentication
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Adds a default header
	 *
	 * @param string $headerName  header name (e.g. Token)
	 * @param string $headerValue header value (e.g. 1z8wp3)
	 *
	 * @return Configuration
	 */
	public function addDefaultHeader( $headerName, $headerValue ) {
		if ( ! is_string( $headerName ) ) {
			throw new InvalidArgumentException( __( 'Header name must be a string.', 'wp-visa-developer-center-apis' ) );
		}

		$this->defaultHeaders[ $headerName ] =  $headerValue;

		return $this;
	}

	/**
	 * Gets the default header
	 *
	 * @return array An array of default header(s)
	 */
	public function getDefaultHeaders() {
		return $this->defaultHeaders;
	}

	/**
	 * Deletes a default header
	 *
	 * @param string $headerName the header to delete
	 *
	 * @return Configuration
	 */
	public function deleteDefaultHeader( $headerName ) {
		unset( $this->defaultHeaders[ $headerName] );

		return $this;
	}

	/**
	 * Sets the host
	 *
	 * @param string $host Host
	 *
	 * @return Configuration
	 */
	public function setHost( $host ) {
		$this->host = $host;

		return $this;
	}

	/**
	 * Gets the host
	 *
	 * @return string Host
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * Sets the user agent of the api client
	 *
	 * @param string $userAgent the user agent of the api client
	 *
	 * @return Configuration
	 */
	public function setUserAgent( $userAgent ) {
		if ( ! is_string( $userAgent ) ) {
			throw new InvalidArgumentException( __( 'User-agent must be a string.', 'wp-visa-developer-center-apis' ) );
		}

		$this->userAgent = $userAgent;

		return $this;
	}

	/**
	 * Gets the user agent of the api client
	 *
	 * @return string user agent
	 */
	public function getUserAgent() {
		return $this->userAgent;
	}

	/**
	 * Sets the HTTP timeout value
	 *
	 * @param integer $seconds Number of seconds before timing out [set to 0 for no timeout]
	 *
	 * @return Configuration
	 */
	public function setCurlTimeout( $seconds ) {
		if ( ! is_numeric( $seconds ) || $seconds < 0) {
			throw new InvalidArgumentException( __( 'Timeout value must be numeric and a non-negative number.', 'wp-visa-developer-center-apis' ) );
		}

		$this->curlTimeout = $seconds;

		return $this;
	}

	/**
	 * Gets the HTTP timeout value
	 *
	 * @return string HTTP timeout value
	 */
	public function getCurlTimeout() {
		return $this->curlTimeout;
	}

	/**
	 * Sets the HTTP connect timeout value
	 *
	 * @param integer $seconds Number of seconds before connection times out [set to 0 for no timeout]
	 *
	 * @return Configuration
	 */
	public function setCurlConnectTimeout( $seconds ) {
		if ( ! is_numeric( $seconds ) || $seconds < 0) {
			throw new InvalidArgumentException( __( 'Connect timeout value must be numeric and a non-negative number.', 'wp-visa-developer-center-apis' ) );
		}

		$this->curlConnectTimeout = $seconds;

		return $this;
	}

	/**
	 * Gets the HTTP connect timeout value
	 *
	 * @return string HTTP connect timeout value
	 */
	public function getCurlConnectTimeout() {
		return $this->curlConnectTimeout;
	}

	/**
	 * Sets the HTTP Proxy Host
	 *
	 * @param string $proxyHost HTTP Proxy URL
	 *
	 * @return Configuration
	 */
	public function setCurlProxyHost( $proxyHost ) {
		$this->proxyHost = $proxyHost;

		return $this;
	}

	/**
	 * Gets the HTTP Proxy Host
	 *
	 * @return string
	 */
	public function getCurlProxyHost() {
		return $this->proxyHost;
	}

	/**
	 * Sets the HTTP Proxy Port
	 *
	 * @param integer $proxyPort HTTP Proxy Port
	 *
	 * @return Configuration
	 */
	public function setCurlProxyPort( $proxyPort ) {
		$this->proxyPort = $proxyPort;

		return $this;
	}

	/**
	 * Gets the HTTP Proxy Port
	 *
	 * @return integer
	 */
	public function getCurlProxyPort() {
		return $this->proxyPort;
	}

	/**
	 * Sets the HTTP Proxy Type
	 *
	 * @param integer $proxyType HTTP Proxy Type
	 *
	 * @return Configuration
	 */
	public function setCurlProxyType( $proxyType ) {
		$this->proxyType = $proxyType;

		return $this;
	}

	/**
	 * Gets the HTTP Proxy Type
	 *
	 * @return integer
	 */
	public function getCurlProxyType() {
		return $this->proxyType;
	}

	/**
	 * Sets the HTTP Proxy User
	 *
	 * @param string $proxyUser HTTP Proxy User
	 *
	 * @return Configuration
	 */
	public function setCurlProxyUser( $proxyUser ) {
		$this->proxyUser = $proxyUser;

		return $this;
	}

	/**
	 * Gets the HTTP Proxy User
	 *
	 * @return string
	 */
	public function getCurlProxyUser() {
		return $this->proxyUser;
	}

	/**
	 * Sets the HTTP Proxy Password
	 *
	 * @param string $proxyPassword HTTP Proxy Password
	 *
	 * @return Configuration
	 */
	public function setCurlProxyPassword( $proxyPassword ) {
		$this->proxyPassword = $proxyPassword;

		return $this;
	}

	/**
	 * Gets the HTTP Proxy Password
	 *
	 * @return string
	 */
	public function getCurlProxyPassword() {
		return $this->proxyPassword;
	}

	/**
	 * Sets debug flag
	 *
	 * @param bool $debug Debug flag
	 *
	 * @return Configuration
	 */
	public function setDebug( $debug ) {
		$this->debug = $debug;

		return $this;
	}

	/**
	 * Gets the debug flag
	 *
	 * @return bool
	 */
	public function getDebug() {
		return $this->debug;
	}

	/**
	 * Sets the debug file
	 *
	 * @param string $debugFile Debug file
	 *
	 * @return Configuration
	 */
	public function setDebugFile( $debugFile ) {
		$this->debugFile = $debugFile;

		return $this;
	}

	/**
	 * Gets the debug file
	 *
	 * @return string
	 */
	public function getDebugFile() {
		return $this->debugFile;
	}

	/**
	 * Sets the temp folder path
	 *
	 * @param string $tempFolderPath Temp folder path
	 *
	 * @return Configuration
	 */
	public function setTempFolderPath( $tempFolderPath ) {
		$this->tempFolderPath = $tempFolderPath;

		return $this;
	}

	/**
	 * Gets the temp folder path
	 *
	 * @return string Temp folder path
	 */
	public function getTempFolderPath() {
		return $this->tempFolderPath;
	}

	/**
	 * Sets the private Key path
	 *
	 * @param string $certificatePath Temp folder path
	 *
	 * @return Configuration
	 */
	public function setCertificatePath( $certificatePath ) {
		$this->certificatePath = $certificatePath;

		return $this;
	}

	/**
	* Sets the caCert path
	*
	* @param string $caCertPath Temp folder path
	*
	* @return Configuration
	*/
	public function setCaCertPath( $caCertPath ) {
		$this->caCertPath = $caCertPath;

		return $this;
	}

	/**
	 * Gets the private Key path
	 *
	 * @return string Temp folder path
	 */
	public function getPrivateKey() {
		return $this->privateKey;
	}

	public function setPrivateKey( $privateKey ) {
		$this->privateKey = $privateKey;

		return $this;
	}

	/** Sets the shared secret **/
	public function setSharedSecret( $sharedSecret ) {
		$this->sharedSecret = $sharedSecret;

		return $this;
	}

	/**
	 * Gets the shared secret
	 *
	 * @return string Temp folder path
	 */
	public function getSharedSecret() {
		return $this->sharedSecret;
	}

	/**
	 * Gets the SSL certificate path
	 *
	 * @return string Temp folder path
	 */
	public function getCertificatePath() {
		return $this->certificatePath;
	}

	/**
	* Gets the SSL CA certificate path
	*
	* @return string Temp folder path
	*/
	public function getCaCertPath() {
		return $this->caCertPath;
	}

	/**
	 * Sets if SSL verification should be enabled or disabled
	 *
	 * @param boolean $sslVerification True if the certificate should be validated, false otherwise
	 *
	 * @return Configuration
	 */
	public function setSSLVerification( $sslVerification ) {
		$this->sslVerification = $sslVerification;

		return $this;
	}

	/**
	 * Gets if SSL verification should be enabled or disabled
	 *
	 * @return boolean True if the certificate should be validated, false otherwise
	 */
	public function getSSLVerification() {
		return $this->sslVerification;
	}

	/**
	 * Gets the default configuration instance
	 *
	 * @return Configuration
	 */
	public static function getDefaultConfiguration() {
		if ( ! self::$defaultConfiguration ) {
			self::$defaultConfiguration = new Configuration();
		}

		return self::$defaultConfiguration;
	}

	/**
	 * Sets the detault configuration instance
	 *
	 * @param Configuration $config An instance of the Configuration Object
	 *
	 * @return void
	 */
	public static function setDefaultConfiguration( Configuration $config ) {
		self::$defaultConfiguration = $config;

		return;
	}

	/**
	 * Gets the essential information for debugging
	 *
	 * @return string The report for debugging
	 */
	public static function toDebugReport() {
		$report  = 'PHP SDK (vces_100) Debug Report:' . PHP_EOL;
		$report .= '    OS: ' . php_uname() . PHP_EOL;
		$report .= '    PHP Version: ' . phpversion() . PHP_EOL;
		$report .= '    OpenAPI Spec Version: v1' . PHP_EOL;
		$report .= '    SDK Package Version: 1.0.0' . PHP_EOL;
		$report .= '    Temp Folder Path: ' . self::getDefaultConfiguration()->getTempFolderPath() . PHP_EOL;

		return $report;
	}
}
