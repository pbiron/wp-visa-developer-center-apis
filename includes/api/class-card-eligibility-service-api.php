<?php

namespace SHC\VDC;

use Exception;
use InvalidArgumentException;

/**
 * Offers Data API.
 *
 * The Offers Data API provides developers a quick and easy way to retrieve offer
 * information from VMORC. The API allows you to retrieve all your available offers
 * or retrieve specific offers. In an offer-specific request, you may choose to
 * filter your accessible offers by certain offer attributes or you may request for
 * offers by its identifiers.
 *
 * @since 0.1.0
 */
class Card_Elibility_Service_API extends API_Base {
	/**
	 * The namespace for our endpoints.
	 *
	 * @var string
	 */
	protected $namespace = '/visacardeligibilityservices/v1/cardeligibility/';

    /**
     * Validate.
     *
     * This resource returns information of the card products eligibility against a program and corresponding card details if configured for that program.
     *
     * @since 0.1.0
     *
     * @param array $postData {
     *     POST data.
     *
     *     @type string $vendorUniqueId Required.  Vendor Unique ID.
     *     @type string $permanentAccountNumber Required.  Payment card number to be used against the Vendor Unique ID.
     *     @type string $requestTimeStamp Required.  Time stamp of the request and in GMT format MM/dd/yyyy hh:mm:ss a.
     *     @type string $correlationId Rerquired.  unique string of characters.
     *     @type string $extendedData Optional.  additional data.
     *     @type string $numberOfAdditionalRedemptions Optional.  Number of additional redemptions.
     *     @type string[] $expirationDate {
     *         Optional.  Expiration date.
     *
     *         @type stirng $month  Optional. Month of Expiration Date in MM format.
     *         @type stirng $year   Optional. Year of Expiration Date in YY or YYYY format.
     *     }
     * }
     * @throws ApiException on non-2xx response
     * @return array {
     *     @type xxx $result
     *     @type string $http_status_code
     *     @type string[] $http_response_headers
     * }
     */
	public function validate( $postData ) {
		$required_params = array(
			'vendorUniqueId',
			'permanentAccountNumber',
			'correlationId',
		);

		if ( empty( $postData['requestTimeStamp'] ) ) {
			$postData['requestTimeStamp'] = date( 'n/d/Y h:i:s A' );
		}

		// make the API Call
        try {
        	$this->check_required_params( $required_params, $postData );

            return $this->apiClient->callApi(
            	$this->namespace . __FUNCTION__,
            	__FUNCTION__,
                'POST',
                null,
            	$postData
            );
        }
        catch ( Exception $ex ) {
        	throw $ex;
        }
	}

    /**
     * Prepay.
     *
     * This resource returns information of the card products eligibility against a program and corresponding card details if configured for that program.
     *
     * @since 0.1.0
     *
     * @param array $postData {
     *     POST data.
     *
     *     @type string $vendorUniqueId Required.  Vendor Unique ID.
     *     @type string $permanentAccountNumber Required.  Payment card number to be used against the Vendor Unique ID.
     *     @type string $requestTimeStamp Required.  Time stamp of the request and in GMT format MM/dd/yyyy hh:mm:ss a.
     *     @type string $correlationId Rerquired.  unique string of characters.
     *     @type string $extendedData Optional.  additional data.
     *     @type string $numberOfAdditionalRedemptions Optional.  Number of additional redemptions.
     *     @type string[] $expirationDate {
     *         Optional.  Expiration date.
     *
     *         @type stirng $month  Optional. Month of Expiration Date in MM format.
     *         @type stirng $year   Optional. Year of Expiration Date in YY or YYYY format.
     *     }
     * }
     * @throws ApiException on non-2xx response
     * @return array {
     *     @type xxx $result
     *     @type string $http_status_code
     *     @type string[] $http_response_headers
     * }
     */
    public function prepay( $postData ) {
    	$required_params = array(
    		'vendorUniqueId',
    		'permanentAccountNumber',
    		'correlationId',
    	);

    	if ( empty( $postData['requestTimeStamp'] ) ) {
    		$postData['requestTimeStamp'] = date( 'n/d/Y h:i:s A' );
    	}

    	// make the API Call
    	try {
    		$this->check_required_params( $required_params, $postData );

    		return $this->apiClient->callApi(
	    		$this->namespace . __FUNCTION__,
	    		__FUNCTION__,
	    		'POST',
	    		null,
	    		$postData
    		);
    	}
    	catch ( Exception $ex ) {
    		throw $ex;
    	}
    }

    /**
     * Redeem.
     *
     * This resource returns information of the card products eligibility against a program and corresponding card details if configured for that program.
     *
     * @since 0.1.0
     *
     * @param array $postData {
     *     POST data.
     *
     *     @type string $vendorUniqueId Required.  Vendor Unique ID.
     *     @type string $permanentAccountNumber Required.  Payment card number to be used against the Vendor Unique ID.
     *     @type string $requestTimeStamp Required.  Time stamp of the request and in GMT format MM/dd/yyyy hh:mm:ss a.
     *     @type string $correlationId Rerquired.  unique string of characters.
     *     @type string $extendedData Optional.  additional data.
     *     @type string $numberOfAdditionalRedemptions Optional.  Number of additional redemptions.
     *     @type string[] $expirationDate {
     *         Optional.  Expiration date.
     *
     *         @type stirng $month  Optional. Month of Expiration Date in MM format.
     *         @type stirng $year   Optional. Year of Expiration Date in YY or YYYY format.
     *     }
     * }
     * @throws ApiException on non-2xx response
     * @return array {
     *     @type xxx $result
     *     @type string $http_status_code
     *     @type string[] $http_response_headers
     * }
     */
    public function redeem( $postData ) {
    	$required_params = array(
    		'vendorUniqueId',
    		'permanentAccountNumber',
    		'correlationId',
    	);

    	if ( empty( $postData['requestTimeStamp'] ) ) {
    		$postData['requestTimeStamp'] = date( 'n/d/Y h:i:s A' );
    	}

    	// make the API Call
    	try {
    		$this->check_required_params( $required_params, $postData );

    		// note: unlike all other VDC APIs, the redeem endpoint is in a separate namespace
    		//       than other endpoints in this API :-(
    		return $this->apiClient->callApi(
	    		dirname( $this->namespace ) . '/promo/' . __FUNCTION__,
	    		__FUNCTION__,
	    		'POST',
	    		null,
	    		$postData
    		);
    	}
    	catch ( Exception $ex ) {
    		throw $ex;
    	}
    }
}
