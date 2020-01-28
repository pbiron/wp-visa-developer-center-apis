<?php

namespace SHC\VDC;

defined( 'ABSPATH' ) || die;

/**
 * Offers Data API.
 *
 * The Offers Data API provides developers a quick and easy way to retrieve offer
 * information from VMORC. The API allows you to retrieve all your available offers
 * or retrieve specific offers. In an offer-specific request, you may choose to
 * filter your accessible offers by certain offer attributes or you may request for
 * offers by its identifiers.
 *
 * For more information about this API, see https://developer.visa.com/capabilities/vmorc/reference#vmorc__offers_data_api
 *
 * @since 0.1.0
 */
class Offers_Command extends VDC_Command {
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
	protected $assoc_args_mapping = array(
		'non_card_attribute' => 'non_cardAttribute',
	);

	/**
	 * Retrieve Offers By Offer Id.
	 *
	 * Retrieve the offer and any of its translations (if any exist). Each specified offerID may return one(if no translations exist) or more offer content objects.
	 *
	 * For more information about this endpoint, see https://developer.visa.com/capabilities/vmorc/reference#vmorc__offers_data_api__v1__retrieve_offers_by_offer_id
	 *
	 * <offerid>...
	 * : Retrieve offers by their offer ids.
	 *
	 * [--updatefrom=<updatefrom>]
	 * : Request for offers that are updated after a specified date (in GMT). Accepts a date formatted by: yyyyMMdd.  Default: null.
	 *
	 * [--updateto=<updateto>]
	 * : Request for offers that are updated before a specified date (in GMT). Accepts a date formatted by: yyyyMMdd.  Default: null.
	 *
	 * [--start_index=<start_index>]
	 * : A maximum of 500 offer results are returned in the offer response. The index indicates which offer within the sorted offer results to start returning in the offer response. Accepts an integer. Default set to 1.
	 *
	 * [--max_offers=<max_offers>]
	 * : A maximum of 500 offer results are returned in the offer response. The index indicates the maximum number of offers to return in the response. Accepts an integer (greater than 0; less than or equal to 500). Default set to 500.
	 *
	 * [--vdc_debug]
	 * : Include VDC debugging output.  Note that this is different than WP_CLI's --debug flag.
	 *
	 * [--ssl_verify]
	 * : Enable SSL verification.  Default true.  To disable verification, use --no-ssl_verify.
	 *
	 * @since 0.1.0
	 */
	function byofferid( $args, $assoc_args ) {
		$default_assoc_args = array(
			'updatefrom'  => null,
			'updateto'    => null,
			'start_index' => null,
			'max_offers'  => null,
		);
		$assoc_args = wp_parse_args( $assoc_args, array_merge( $default_assoc_args, $this->common_assoc_args ) );

		$this->setup_config( $assoc_args );
		unset( $assoc_args['vdc_debug'], $assoc_args['verify'] );

		$queryParams            = $this->map_params( $assoc_args );
		$queryParams['offerid'] = implode( ',', $args );
		$this->call_api( __FUNCTION__, $queryParams );

		return;
	}

	/**
	 * Retrieve Offers By Content Id.
	 *
	 * Retrieve the language-specific offer. Each specified contentID returns one offer content object.
	 *
	 * For more information about this endpoint, see https://developer.visa.com/capabilities/vmorc/reference#vmorc__offers_data_api__v1__retrieve_offers_by_content_id
	 *
	 * <contentid>...
	 * : Retrieve offers by their content ids.
	 *
	 * [--updatefrom=<updatefrom>]
	 * : Request for offers that are updated after a specified date (in GMT). Accepts a date formatted by: yyyyMMdd.  Default: null.
	 *
	 * [--updateto=<updateto>]
	 * : Request for offers that are updated before a specified date (in GMT). Accepts a date formatted by: yyyyMMdd.  Default: null.
	 *
	 * [--start_index=<start_index>]
	 * : A maximum of 500 offer results are returned in the offer response. The index indicates which offer within the sorted offer results to start returning in the offer response. Accepts an integer. Default set to 1.
	 *
	 * [--max_offers=<max_offers>]
	 * : A maximum of 500 offer results are returned in the offer response. The index indicates the maximum number of offers to return in the response. Accepts an integer (greater than 0; less than or equal to 500). Default set to 500.
	 *
	 * [--vdc_debug]
	 * : Include VDC debugging output.  Note that this is different than WP_CLI's --debug flag.
	 *
	 * [--ssl_verify]
	 * : Enable SSL verification.  Default true.  To disable verification, use --no-ssl_verify.
	 *
	 * @since 0.1.0
	 */
	function bycontentid( $args, $assoc_args ) {
		$default_assoc_args = array(
			'updatefrom'  => null,
			'updateto'    => null,
			'start_index' => null,
			'max_offers'  => null,
		);
		$assoc_args = wp_parse_args( $assoc_args, array_merge( $default_assoc_args, $this->common_assoc_args ) );

		$this->setup_config( $assoc_args );
		unset( $assoc_args['vdc_debug'], $assoc_args['verify'] );

		$queryParams              = $this->map_params( $assoc_args );
		$queryParams['contentid'] = implode( ',', $args );
		$this->call_api( __FUNCTION__, $queryParams );

		return;
	}

	/**
	 * Retrieve Offers By Filter.
	 *
	 * Retrieve a filtered set of offers by specifying reference criteria noted below. Reference data options in the same criteria apply an "OR" relationship and must be comma-separated in the request. Reference data options in different criteria apply an "AND" relationship and each data type is separated using an "&". The key identifiers of each available reference data option is returned through the reference data requests.
	 *
	 * For more information about this endpoint, see https://developer.visa.com/capabilities/vmorc/reference#vmorc__offers_data_api__v1__retrieve_offers_by_filter
	 *
	 * [--business_segment=<business_segment>]
	 * : Filter offers by business segments. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--card_payment_type=<card_payment_type>]
	 * : Filter offers by card payment types. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--card_product=<card_product>]
	 * : Filter offers by card products. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--category=<category>]
	 * : Filter offers by offer categories. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--subcategory=<subcategory>]
	 * : Filter offers by offer subcategories. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--merchant=<merchange>]
	 * : Filter offers by merchants. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--program=<program>]
	 * : Filter offers by programs. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--promotion_channel=<promotion_channel>]
	 * : Filter offers by promotion channels. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--promoting_region=<promoting_region>]
	 * : Filter offers by promoting regions. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--promoting_country=<promoting_country>]
	 * : Filter offers by promoting countries. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--redemption_region=<redemption_region>]
	 * : Filter offers by redemption regions. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--redemption_country=<redemption_country>]
	 * : Filter offers by redemption countries. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--merchant_region=<merchant_region>]
	 * : Filter for offers that have been assigned a merchant address in at least one merchant address region parameter.
	 *
	 * [--merchant_county=<merchant_county>]
	 * : Filter for offers that have been assigned a merchant address in at least one merchant address country parameter.
	 *
	 * [--language=<language>]
	 * : Filter offers by offer languages. Provide one "key" integer or a comma-delimited string of "key" integers.
	 *
	 * [--expired=<expired>]
	 * : Request for expired offers. Provide a boolean value. Default set to value to "false".
	 * ---
	 * default: false
	 * options:
	 *   - true
	 *   - false
	 *
	 * [--validfrom=<validfrom>]
	 * : Request for offers where the offer's redemption end date is on or after the provided date (in GMT). Accepts a date formatted by: yyyyMMdd e.g. 1, If validfrom=20150101 is provided, this returns eligible offers that end on or after January 1, 2015 e.g. 2, If validfrom=20150101&validto=20150131, this will return eligible offers that end on or after January 1, 2015 and start on or before January 31, 2015 (valid during at least one day in January 2015).
	 *
	 * [--validto=<validto>]
	 * : Request for offers where the offer’s redemption start date is before or on the provided date (in GMT). Accepts a date formatted by: yyyyMMdd e.g. 1, If validto=20150131 is provided, this will return eligible offers that start before or on January 31, 2015 e.g. 2, If validfrom=20150101&validto=20150131, this will return eligible offers that end on or after January 1, 2015 and start on or before January 31, 2015 (valid during at least one day in January 2015).
	 *
	 * [--promotedfrom=<promotedfrom>]
	 * : Request for offers where the offer’s promotion end date is on or after the provided date (in GMT). Accepts a date formatted by: yyyyMMdd e.g. 1, If promotedfrom=20150101 is provided, this will / return eligible offers where the promotion ends on or after January 1, 2015 e.g. 2, If promotedfrom=20150101&promotedto=20150131, this will return eligible offers where the promotion ends on or after January 1, 2015 and start on or before January 31, 2015 (promoted at least one day in January 2015).
	 *
	 * [--promotedto=<promotedto>]
	 * : Request for offers where the offer’s promotion start date is before or on the provided date (in GMT). Accepts a date formatted by: yyyyMMdd e.g. 1, If promotedto=20150131 is provided, this will return eligible offers where the promotion starts before or on January 31, 2015 e.g. 2, If promotedfrom=20150101&promotedto=20150131, this will return eligible offers where the promotion ends on or after January 1, 2015 and start on or before January 31, 2015 (promoted at least one day in January 2015).
	 *
	 * [--updatefrom=<updatefrom>]
	 * : Request for offers that are updated after a specified date (in GMT). Accepts a date formatted by: yyyyMMdd.  Default: null.
	 *
	 * [--updateto=<updateto>]
	 * : Request for offers that are updated before a specified date (in GMT). Accepts a date formatted by: yyyyMMdd.  Default: null.
	 *
	 * [--featured=<featured>]
	 * : Request for featured offers. Provide a boolean value.
	 * ---
	 * default: false
	 * options:
	 *   - true
	 *   - false
	 *
	 * [--start_index=<start_index>]
	 * : A maximum of 500 offer results are returned in the offer response. The index indicates which offer within the sorted offer results to start returning in the offer response. Accepts an integer. Default set to 1.
	 *
	 * [--max_offers=<max_offers>]
	 * : A maximum of 500 offer results are returned in the offer response. The index indicates the maximum number of offers to return in the response. Accepts an integer (greater than 0; less than or equal to 500). Default set to 500.
	 *
	 * [--bins=<bins>]
	 * : Request for offers that fulfill one or more BIN options by inputting the exact desired bin value(s). Comma-delimit for multiple values.
	 *
	 * [--rpins=<rpins>]
	 * : Request for offers that fulfill one or more RPIN options by inputting the exact desired rpin value(s). Comma-delimit for multiple values.
	 *
	 * [--bins_to_rpins=<bins_to_rpins>]
	 * : Request for offers that fulfill one or more BIN to RPIN pairing options by inputting the exact desired bin value, a tilde("~"), and the exact rpin value.
	 *
	 * [--accountranges=<accountranges>]
	 * : Request for offers that fulfill a specific Account Range by providing either a "from" prefix value OR a "to" prefix value OR a "from" and a "to" prefix values. Each provided prefix value must be a minimum of 4 digits. Each account range request must contain a colon(":") to distinguish between the "from" and "to" values (even if only one boundary is provided).
	 *
	 * [--accountranges_to_rpins=<accountranges_to_rpins>]
	 * : Request for offers that fulfill a specific Account Range to RPIN pairing by providing either a "from" prefix value ~ RPIN OR a "to" prefix value ~ RPIN OR a "from" and a "to" prefix values ~ RPIN. Each provided account range prefix value must be a minimum of 4 digits. Each account range portion of the request must contain a colon(":") to distinguish between the "from" and "to" values (even if only one boundary is provided). Use a tilde("~") to separate the account range from the exact desired rpin.
	 *
	 * [--pans=<pans>]
	 * : Request for offers by PAN (must be a minimum of 16 digits). The provided PAN is padded-right with "0" to a length of 21 digits. The system returns the offers where the padded value is within the subset of at least one of the offer's account range assignments.
	 *
	 * [--non_card_attribute=<non_cardAttribute>]
	 * : Request for offers that do not have assignments to card attribute fields. Provide a boolean value. Default sets value to "false".
	 * ---
	 * default: false
	 * options:
	 *   - true
	 *   - false
	 *
	 * [--origin=<origin>]
	 * : Required for applying a geolocation filter. Input the origin by specifying the latitude, a comma (","), and the longitude. Coordinates must be inputted in decimal degree format. The accepted range for latitude is between -90 and 90, inclusive. The accepted range for longitude is between -180 and 180, inclusive.
	 *
	 * [--radius=<radius>]
	 * : Optional for applying a geolocation filter.. A maximum radius of 1000 (kilometers) or 621.371 (miles) is accepted. Default sets to 60 miles (or 100 kilometers if the "unit" geolocation parameter has been set to "km").
	 *
	 * [--unit=<units>]
	 * : Optional for applying a geolocation filter.. Indicate the distance unit of miles or kilometers. Default sets to miles. To use kilometers, specify "km".
	 * ---
	 * default: miles
	 * options:
	 *   - miles
	 *   - km
	 *
	 * [--non_geo=<non_geos>]
	 * : Optional for applying a geolocation filter.. Request for offers that have not been assigned merchant addresses with geo-location(latitude/longitude) coordinates. At minimum, an origin must be also provided to call this flag. Accepts a boolean value - By default, the boolean value is set to false.
	 * ---
	 * default: false
	 * options:
	 *   - true
	 *   - false
	 *
	 * [--vdc_debug]
	 * : Include VDC debugging output.  Note that this is different than WP_CLI's --debug flag.
	 *
	 * [--ssl_verify]
	 * : Enable SSL verification.  Default true.  To disable verification, use --no-ssl_verify.
	 *
	 * @since 0.1.0
	 */
	function byfilter( $args, $assoc_args ) {
		$default_assoc_args = array(
			'business_segment'       => null,
			'card_payment_type'      => null,
			'card_product'           => null,
			'category'               => null,
			'subcategory'            => null,
			'merchant'               => null,
			'program'                => null,
			'promotion_channel'      => null,
			'promoting_region'       => null,
			'promoting_country'      => null,
			'redemption_region'      => null,
			'redemption_country'     => null,
			'merchant_region'        => null,
			'merchant_county'        => null,
			'language'               => null,
			'expired'                => null,
			'valid_from'             => null,
			'valid_to'               => null,
			'promoted_from'          => null,
			'promoted_to'            => null,
			'update_from'            => null,
			'update_to'              => null,
			'featured'               => null,
			'start_index'            => null,
			'max_offers'             => null,
			'bins'                   => null,
			'rpins'                  => null,
			'bins_to_rpins'          => null,
			'accountranges'          => null,
			'accountranges_to_rpins' => null,
			'pans'                   => null,
			'non_card_attribute'     => null,
			'origin'                 => null,
			'radius'                 => null,
			'unit'                   => null,
			'non_geo'                => null,
		);

		$assoc_args = wp_parse_args( $assoc_args, array_merge( $default_assoc_args, $this->common_assoc_args ) );

		$this->setup_config( $assoc_args );
		unset( $assoc_args['vdc_debug'], $assoc_args['verify'] );

		$queryParams = $this->map_params( $assoc_args );
		$this->call_api( __FUNCTION__, $queryParams );

		return;
	}

	/**
	 * Retrieve All Offers.
	 *
	 * Retrieve all accessible offers in the response.
	 *
	 * For more information about this endpoint, see https://developer.visa.com/capabilities/vmorc/reference#vmorc__offers_data_api__v1__retrieve_all_offers
	 *
	 * [--start_index=<start_index>]
	 * : A maximum of 500 offer results are returned in the offer response. The index indicates which offer within the sorted offer results to start returning in the offer response. Accepts an integer. Default set to 1.
	 *
	 * [--max_offers=<max_offers>]
	 * : A maximum of 500 offer results are returned in the offer response. The index indicates the maximum number of offers to return in the response. Accepts an integer (greater than 0; less than or equal to 500). Default set to 500.
	 *
	 * [--vdc_debug]
	 * : Include VDC debugging output.  Note that this is different than WP_CLI's --debug flag.
	 *
	 * [--ssl_verify]
	 * : Enable SSL verification.  Default true.  To disable verification, use --no-ssl_verify.
	 *
	 * @since 0.1.0
	 */
	function all( $args, $assoc_args ) {
		$default_assoc_args = array(
			'start_index' => null,
			'max_offers'  => null,
		);
		$assoc_args = wp_parse_args( $assoc_args, wp_parse_args( $default_assoc_args, $this->common_assoc_args ) );

		$this->setup_config( $assoc_args );
		unset( $assoc_args['vdc_debug'], $assoc_args['verify'] );

		$queryParams = $this->map_params( $assoc_args );
		$this->call_api( __FUNCTION__, $queryParams );

		return;
	}

	/**
	 * Constructor.
	 *
	 * Instantiate the VDC API object.
	 *
	 * @since 0.1.0
	 */
	function __construct() {
		parent::__construct();

		$this->api = new Offers_Data_API();
	}
}
