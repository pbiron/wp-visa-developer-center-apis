<?php

namespace SHC\VDC;

/**
 * VDC Project configuration.
 *
 * Enter the relevant information from the VDC Project.
 *
 * @since 0.1.0
 */
class Project_Config {
	public static function getUserName() {
		$UserName = '';

		return $UserName;
	}

	public static function getPassword() {
		$Password = '';

		return $Password;
	}

	public static function getCertificatePath() {
		$CertificatePath = '';

		return __DIR__ . '/certs/' . $CertificatePath;
	}

	public static function getCaCertPath() {
		// @todo for some reason, curl is producing a "error setting certificate
		//       verify locations: "CAfile: xxx, CApath: none" when I set this.
		//       so, for now we just leave it empty and just use the --no-ssl_verify flag to
		//       the WP-CLI commands.
//		$CaCertPath = 'DigiCertGlobalRootCA.crt';
//		return __DIR__ . '/certs/' . $CaCertPath;

		$CaCertPath = '';

		return $CaCertPath;
	}

	public static function getPrivateKeyPath() {
		$PrivateKeyPath = '';

		return __DIR__ . '/certs/' . $PrivateKeyPath;
	}

	public static function getSharedSecret() {
		$SharedSecret = '';

		return $SharedSecret;
	}

	public static function getApiKey() {
		$Apikey = '';

		return $Apikey;
	}

	public static function getProxyHost() {
		$ProxyHost ='';

		return $ProxyHost;
	}

	public static function getProxyPort() {
		$ProxyPort = '';

		return $ProxyPort;
	}

	public static function getProxyUser() {
		$ProxyUser = '';

		return $ProxyUser;
	}

	public static function getProxyPassword() {
		$ProxyPassword = '';

		return $ProxyPassword;
	}

	public static function getDefaultVendorUniqueId() {
		$vendorUniqueId = '';

		return $vendorUniqueId;
	}

	public static function getDefaultPermanentAccountNumber() {
		$permanentAccountNumber = '';

		return $permanentAccountNumber;
	}
}
