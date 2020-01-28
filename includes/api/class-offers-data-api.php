<?php

namespace SHC\VDC;

use Exception;

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
class Offers_Data_API extends API_Base {
	/**
	 * The namespace for our endpoints.
	 *
	 * @var string
	 */
	protected $namespace = '/vmorc/offers/v1/';

    /**
     * Retrieve All Offers.
     *
     * Retrieve all accessible offers in the response.
     *
     * @since 0.1.0
     *
     * @param array $queryParams {
     *     Query parameters.
     *
     *     @type string $start_index Optional.  A maximum of 500 offer results may be returned in the response. The parameter specifies the index of the total available offer results to start returning in the response.
     *     @type string $max_offers  Optional.  A maximum of 500 offer results are returned in the offer response. The index indicates the maximum number of offers to return in the response. Accepts an integer (greater than 0; less than or equal to 500). Default set to 500.
     * }
     * @throws ApiException on non-2xx response
     * @return array {
     *     @type xxx $result
     *     @type string $http_status_code
     *     @type string[] $http_response_headers
     * }
     */
    public function all( $queryParams ) {
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
     * Retrieve Offers By Content Id.
     *
     * Retrieve the language-specific offer. Each specified contentID returns one offer content object.
     *
     * @since 0.1.0
     *
     * @param array $queryParams {
     *     Query parameters.
     *
     *     @type string $contentid   Required.  Retrieve offers by their content ids. Provide an content id integer or a comma-delimited string of content id integers.
     *     @type string $updatefrom  Optional.  Request for offers that are updated after a specified date (in GMT). Accepts a date formatted by: yyyyMMdd.
     *     @type string $updateto    Optional.  Request for offers that are updated before a specified date (in GMT). Accepts a date formatted by: yyyyMMdd.
     *     @type string $start_index Optional.  A maximum of 500 offer results are returned in the offer response. The index indicates which offer within the sorted offer results to start returning in the offer response. Accepts an integer. Default set to 1.
     *     @type string $max_offers  Optional.  A maximum of 500 offer results are returned in the offer response. The index indicates the maximum number of offers to return in the response. Accepts an integer (greater than 0; less than or equal to 500). Default set to 500.
     * }
     * @throws ApiException on non-2xx response
     * @return array {
     *     @type xxx $result
     *     @type string $http_status_code
     *     @type string[] $http_response_headers
     * }
     */
    public function bycontentid( $queryParams ) {
    	$required_params = array(
    		'contentid',
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
     * Retrieve Offers By Filter.
     *
     * Retrieve a filtered set of offers by specifying reference criteria noted below.
     * Reference data options in the same criteria apply an "OR" relationship and must
     * be comma-separated in the request. Reference data options in different criteria
     * apply an "AND" relationship and each data type is separated using an "&". The key
     * identifiers of each available reference data option is returned through the reference
     * data requests.
     *
     * @since 0.1.0
     *
     * @param array $queryParams {
     *     Query parameters.
     *
     *     @type string $business_segment       Filter offers by business segments. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers  Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#business_segments\&quot;&gt;business_segments&lt;/a&gt; for sample values and keys. (optional)
     *     @type  string $card_payment_type     Filter offers by card payment types. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers  Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#card_payment_types\&quot;&gt;card_payment_types&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $card_product           Filter offers by card products. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers  Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#card_product\&quot;&gt;card_product&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $category               Filter offers by offer categories. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers  Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#category_and_subcategory\&quot;&gt;category_subcategory&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $subcategory            Filter offers by offer subcategories. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers  Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#category_and_subcategory\&quot;&gt;category_subcategory&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $merchant               Filter offers by merchants. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers   Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#merchant\&quot;&gt;merchant&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $program                Filter offers by programs. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers  Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#program\&quot;&gt;program&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $promotion_channel      Filter offers by promotion channels. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers   Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#promotion_channel\&quot;&gt;promotion_channel&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $promoting_region       Filter offers by promoting regions. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers  Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#region\&quot;&gt;region&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $promoting_country      Filter offers by promoting countries. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers    Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#country\&quot;&gt;country&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $redemption_region      Filter offers by redemption regions. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers    Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#region\&quot;&gt;region&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $redemption_country     Filter offers by redemption countries. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers  Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#country\&quot;&gt;country&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $merchant_region        Filter for offers that have been assigned a merchant address in at least one merchant address region parameter   Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#region\&quot;&gt;region&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $merchant_county        Filter for offers that have been assigned a merchant address in at least one merchant address country parameter    Refer to &lt;a href&#x3D;\&quot;/guides/request_response_codes#country\&quot;&gt;country&lt;/a&gt; for sample values and keys. (optional)
     *     @type string $language               Filter offers by offer languages. Provide one \&quot;key\&quot; integer or a comma-delimited string of \&quot;key\&quot; integers (optional)
     *     @type string $expired                Request for expired offers. Provide a boolean value. Default set to value to \&quot;false\&quot;. (optional)
     *     @type string $validfrom              Request for offers where the offer&#39;s redemption end date is on or after the provided date (in GMT). Accepts a date formatted by: yyyyMMdd e.g. 1, If validfrom&#x3D;20150101 is provided, this returns eligible offers that end on or after January 1, 2015 e.g. 2, If validfrom&#x3D;20150101&amp;validto&#x3D;20150131, this will return eligible offers that end on or after January 1, 2015 and start on or before January 31, 2015 (valid during at least one day in January 2015). (optional)
     *     @type string $validto                Request for offers where the offer’s redemption start date is before or on the provided date (in GMT). Accepts a date formatted   by: yyyyMMdd e.g. 1, If validto&#x3D;20150131 is provided, this will     return eligible offers that start before or on January 31,       2015 e.g. 2, If validfrom&#x3D;20150101&amp;validto&#x3D;20150131, this will return eligible offers that end on or after January 1, 2015 and start on or before January 31, 2015 (valid during at least one day in January 2015). (optional)
     *     @type string $promotedfrom           Request for offers where the offer’s promotion end date is on or  after the provided date (in GMT). Accepts a date formatted by: yyyyMMdd e.g. 1, If promotedfrom&#x3D;20150101 is provided, this will / return eligible offers where the promotion ends on or after January 1, 2015 e.g. 2, If promotedfrom&#x3D;20150101&amp;promotedto&#x3D;20150131, this will return eligible offers where the promotion ends on or after January 1, 2015 and start on or before January 31, 2015 (promoted at least one day in January 2015). (optional)
     *     @type string $promotedto             Request for offers where the offer’s promotion start date is before or on the provided date (in GMT). Accepts a date formatted by: yyyyMMdd e.g. 1, If promotedto&#x3D;20150131 is provided, this will return eligible offers where the promotion starts before or on January 31, 2015 e.g. 2, If promotedfrom&#x3D;20150101&amp;promotedto&#x3D;20150131, this will return eligible offers where the promotion ends on or after January 1, 2015 and start on or before January 31, 2015 (promoted at least one day in January 2015). (optional)
     *     @type string $updatefrom             Request for offers where the provided date is before or on an offer’s last modified date/time (in GMT). Accepts a date formatted by: yyyyMMdd (optional)
     *     @type string $updateto               Request for offers where the provided date is after or on an       offer’s last modified date/time (in GMT). Accepts a date     formatted by: yyyyMMdd (optional)
     *     @type string $featured               Request for featured offers. Provide a boolean value. (optional)
     *     @type string $start_index            A maximum of 500 offer results are returned in the offer response. The index indicates which offer within the sorted offer results to start returning in the offer response. Accepts an integer. Default set to 1 (optional)
     *     @type string $max_offers             Optional. A maximum of 500 offer results are returned in the offer response. The index indicates the maximum number of offers to return in the response. Accepts an integer (greater than 0; less than or equal to 500). Default set to 500 (optional)
     *     @type string $bins                   Request for offers that fulfill one or more BIN options by inputting the exact desired bin value(s). Comma-delimit for multiple values (optional)
     *     @type string $rpins                  Request for offers that fulfill one or more RPIN options by inputting the exact desired rpin value(s). Comma-delimit for multiple values (optional)
     *     @type string $bins_to_rpins          Request for offers that fulfill one or more BIN to RPIN pairing options by inputting the exact desired bin value, a tilde(\&quot;~\&quot;), and the exact rpin value. (optional)
     *     @type string $accountranges          Request for offers that fulfill a specific Account Range by     providing either  a \&quot;from\&quot; prefix value OR a \&quot;to\&quot; prefix value OR a \&quot;from\&quot; and a \&quot;to\&quot; prefix values. Each provided prefix value   must be a minimum of 4 digits. Each account range request must contain a colon(\&quot;:\&quot;) to distinguish between the \&quot;from\&quot; and \&quot;to\&quot; values (even if only one boundary is provided). (optional)
     *     @type string $accountranges_to_rpins Request for offers that fulfill a specific Account Range to RPIN pairing by providing either a \&quot;from\&quot; prefix value ~ RPIN OR a \&quot;to\&quot; prefix value ~ RPIN OR a \&quot;from\&quot; and a \&quot;to\&quot; prefix values ~ RPIN. Each provided account range prefix value must be a minimum of 4 digits. Each account range portion of the request must contain a colon(\&quot;:\&quot;) to distinguish between the \&quot;from\&quot; and \&quot;to\&quot; values (even if only one boundary is provided). Use a tilde(\&quot;~\&quot;) to separate the account range from the exact desired rpin. (optional)
     *     @type string $pans                   Request for offers by PAN (must be a minimum of 16 digits). The provided PAN is padded-right with \&quot;0\&quot; to a length of 21 digits. The system returns the offers where the padded value is within the subset of at least one of the offer&#39;s account range assignments. (optional)
     *     @type string $non_card_attribute     Request for offers that do not have assignments to card attribute fields. Provide a boolean value. Default sets value to \&quot;false\&quot;. (optional)
     *     @type string $origin                 Required for applying a geolocation filter. Input the origin by specifying the latitude, a comma (\&quot;,\&quot;), and the longitude. Coordinates must be inputted in decimal degree format. The accepted range for latitude is between -90 and 90, inclusive. The accepted range for longitude is between -180 and 180, inclusive. (optional)
     *     @type string $radius                 Optional for applying a geolocation filter.. A maximum radius of 1000 (kilometers) or 621.371 (miles) is accepted. Default sets to 60 miles (or 100 kilometers if the \&quot;unit\&quot; geolocation parameter has been set to \&quot;km\&quot;). (optional)
     *     @type string $unit                   Optional for applying a geolocation filter.. Indicate the distance unit of miles or kilometers. Default sets to miles. To use kilometers, specify \&quot;km\&quot;. (optional)
     *     @type string $non_geo                Optional for applying a geolocation filter.. Request for offers that have not been assigned merchant addresses with geo-location(latitude/longitude) coordinates. At minimum, an origin must be also provided to call this flag. Accepts a boolean value - By default, the boolean value is set to false. (optional)
     * }
     * @throws ApiException on non-2xx response
     * @return array {
     *     @type xxx $result
     *     @type string $http_status_code
     *     @type string[] $http_response_headers
     * }
     */
    public function byfilter( $queryParams ) {
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
     * Retrieve Offers By Offer Id.
     *
     * Retrieve the offer and any of its translations (if any exist). Each specified
     * offerID may return one(if no translations exist) or more offer content objects.
     *
     * @since 0.1.0
     *
     * @param array $queryParams {
     *     Query parameters.
     *
     *     @type string $offerid     Requried.  Retrieve offers by their offer ids. Provide an offer id integer or a comma-delimited string of offer id integers.
     *     @type string $updatefrom  Optional.  Request for offers that are updated after a specified date (in GMT). Accepts a date formatted by: yyyyMMdd.
     *     @type string $updateto    Optional.  Request for offers that are updated before a specified date (in GMT). Accepts a date formatted by: yyyyMMdd.
     *     @type string $start_index Optional.  A maximum of 500 offer results are returned in the offer response. The index indicates which offer within the sorted offer results to start returning in the offer response. Accepts an integer. Default set to 1.
     *     @type string $max_offers  Optional.  A maximum of 500 offer results are returned in the offer response. The index indicates the maximum number of offers to return in the response. Accepts an integer (greater than 0; less than or equal to 500). Default set to 500.
     * }
     * @throws ApiException on non-2xx response.
     * @return array {
     *     @type xxx $result
     *     @type string $http_status_code
     *     @type string[] $http_response_headers
     * }
     */
    public function byofferid( $queryParams ) {
    	$required_params = array(
    		'offerid',
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
}
