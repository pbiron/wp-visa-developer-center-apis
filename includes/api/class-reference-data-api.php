<?php

namespace SHC\VDC;

use Exception;

/**
 * Reference Data API.
 *
 * The Reference Data API provides developers the set of available reference data
 * from VMORC. Use the "key" information of each reference data option to conduct
 * filtered offer requests.
 *
 * @since 0.1.0
 */
class Reference_Data_API extends API_Base {
	/**
	 * The namespace for our endpoints.
	 *
	 * @var string
	 */
	protected $namespace = '/vmorc/data/v1/';

    /**
     * Retrieve Data by Merchant Address.
     *
     * Retrieve merchant address information for the requested merchants.
     * The service will only return merchant address information associated to
     * your accessible merchants.
     *
     * @since 0.1.0
     *
     * @param array $queryParams {
     *     Query parameters.
     *
     *     @type string $start_index Optional.  A maximum of 500 offer results may be returned in the response. The parameter specifies the index of the total available offer results to start returning in the response.
     *     @type string $merchantIds  Required.  Returns merchant address data for the requested merchant ids. Separate multiple ids by commas.
     * }
     * @throws ApiException on non-2xx response
     * @return array {
     *     @type xxx $result
     *     @type string $http_status_code
     *     @type string[] $http_response_headers
     * }
     */
    public function merchantAddress( $queryParams ) {
    	$required_params = array(
	    	'merchantIds',
    	);

    	// make the API Call
        try {
        	$this->check_required_params( $required_params, $queryParams );

        	return $this->apiClient->callApi(
            	$this->namespace . __FUNCTION__,
            	__FUNCTION__,
                'GET',
                $queryParams
            );
        }
        catch ( Exception $ex ) {
        	throw $ex;
        }
    }

    /**
     * Retrieve Data by Merchant.
     *
     * Retrieve merchant information. The service will only return merchant
     * information associated to your accessible offers (including expired offers).
     * All responses for retrieving reference data return the language API service
     * so you may determine to which language a languageId may belong.
     *
     * @since 0.1.0
     *
     * @param array $queryParams {
     *     Query parameters.
     *
     *     @type string $start_index Optional.  A maximum of 500 offer results may be returned in the response. The parameter specifies the index of the total available offer results to start returning in the response.
     *     @type string $program  Optional.  Returns merchant data for approved and active offers (includes expired offers) that belong to the specified program.
     * }
     * @throws ApiException on non-2xx response
     * @return array {
     *     @type xxx $result
     *     @type string $http_status_code
     *     @type string[] $http_response_headers
     * }
     */
    public function merchant( $queryParams ) {
    	// make the API Call
    	try {
    		return $this->apiClient->callApi(
	    		$this->namespace . __FUNCTION__,
	    		__FUNCTION__,
	    		'GET',
	    		$queryParams
    		);
    	}
    	catch ( Exception $ex ) {
    		throw $ex;
    	}
    }

    /**
     * Retrieve Data by Reference.
     *
     * Specify the reference data type for a set of reference data values.
     * All responses for retrieving reference data return the language API
	 * service so you may determine to which language a languageId may belong.
     *
     * @since 0.1.0
     *
     * @param array $queryParams {
     *     Query parameters.
     *
     *     @type string $resources Optional.  Request for specific reference data types in theresponse.Provide one reference data type or a comma-delimited string of reference data types. Default returns all available referece data types. Refer to resources for supported resource values.
     *     @type string $languages  Optional.  A comma-delimited string of languageIds. For the reference data that may specify a language, the response will only return translations that match the requested languageIds. Default returns all existing translations. Refer to resources for supported resources that support a specific language.
     *     @type string $programIds  Optional.  Returns reference data for approved and active offers (includes expired offers) that belong to the specified program. Two additional responses of "promoting_countries" and "redemption_countries" are returned in this request.
     * }
     * @throws ApiException on non-2xx response
     * @return array {
     *     @type xxx $result
     *     @type string $http_status_code
     *     @type string[] $http_response_headers
     * }
     */
    public function ref( $queryParams ) {
    	// make the API Call
    	try {
    		return $this->apiClient->callApi(
	    		$this->namespace . __FUNCTION__,
	    		__FUNCTION__,
	    		'GET',
	    		$queryParams
    		);
    	}
    	catch ( Exception $ex ) {
    		throw $ex;
    	}
    }
}
