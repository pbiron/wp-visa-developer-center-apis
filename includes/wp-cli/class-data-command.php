<?php

namespace SHC\VDC;

defined( 'ABSPATH' ) || die;

/**
 * Reference Data API.
 *
 * The Reference Data API provides developers the set of available reference data from VMORC. Use the "key" information of each reference data option to conduct filtered offer requests.
 *
 * For more information about this API, see https://developer.visa.com/capabilities/vmorc/reference#vmorc__reference_data_api
 *
 * @since 0.1.0
 */
class Data_Command extends VDC_Command {
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
		'program_ids' => 'programIds',
	);

	/**
	 * Retrieve Data by Merchant Address.
	 *
	 * Retrieve merchant address information for the requested merchants. The service will only return merchant address information associated to your accessible merchants.
	 *
	 * For more information about this endpoint, see https://developer.visa.com/capabilities/vmorc/reference#vmorc__reference_data_api__v1__retrieve_data_by_merchant_address
	 *
	 * <merchantIds>...
	 * : Returns merchant address data for the requested merchant ids.
	 *
	 * [--start_index=<start_index>]
	 * : A maximum of 500 offer results are returned in the offer response. The index indicates which offer within the sorted offer results to start returning in the offer response. Accepts an integer. Default set to 1.
	 *
	 * [--vdc_debug]
	 * : Include VDC debugging output.  Note that this is different than WP_CLI's --debug flag.
	 *
	 * [--ssl_verify]
	 * : Enable SSL verification.  Default true.  To disable verification, use --no-ssl_verify.
	 *
	 * @since 0.1.0
	 */
	function merchantAddress( $args, $assoc_args ) {
		$default_assoc_args = array(
			'start_index' => null,
		);
		$assoc_args = wp_parse_args( $assoc_args, array_merge( $default_assoc_args, $this->common_assoc_args ) );

		$this->setup_config( $assoc_args );
		unset( $assoc_args['vdc_debug'], $assoc_args['verify'] );

		$queryParams                = $this->map_params( $assoc_args );
		$queryParams['merchantIds'] = implode( ',', $args );
		$this->call_api( __FUNCTION__, $queryParams );

		return;
	}

	/**
	 * Retrieve Data by Merchant.
	 *
	 * Retrieve merchant information. The service will only return merchant information associated to your accessible offers (including expired offers). All responses for retrieving reference data return the language API service so you may determine to which language a languageId may belong.
	 *
	 * For more informationa bout this endpoint, see https://developer.visa.com/capabilities/vmorc/reference#vmorc__reference_data_api__v1__retrieve_data_by_merchant
	 *
	 * [--start_index=<start_index>]
	 * : A maximum of 500 offer results are returned in the offer response. The index indicates which offer within the sorted offer results to start returning in the offer response. Accepts an integer. Default set to 1.
	 *
	 * [--program=<program>]
	 * : Returns merchant data for approved and active offers (includes expired offers) that belong to the specified program.
	 *
	 * [--vdc_debug]
	 * : Include VDC debugging output.  Note that this is different than WP_CLI's --debug flag.
	 *
	 * [--ssl_verify]
	 * : Enable SSL verification.  Default true.  To disable verification, use --no-ssl_verify.
	 *
	 * @since 0.1.0
	 */
	function merchant( $args, $assoc_args ) {
		$default_assoc_args = array(
			'start_index' => null,
			'program' => null,
		);
		$assoc_args = wp_parse_args( $assoc_args, array_merge( $default_assoc_args, $this->common_assoc_args ) );

		$this->setup_config( $assoc_args );
		unset( $assoc_args['vdc_debug'], $assoc_args['verify'] );

		$queryParams = $this->map_params( $assoc_args );
		$this->call_api( __FUNCTION__, $queryParams );

		return;
	}

	/**
	 * Retrieve Data by Reference.
	 *
	 * Specify the reference data type for a set of reference data values. All responses for retrieving reference data return the language API service so you may determine to which language a languageId may belong.
	 *
	 * For more information about this endpoint, see https://developer.visa.com/capabilities/vmorc/reference#vmorc__reference_data_api__v1__retrieve_data_by_reference
	 *
	 * [--resources=<resources>]
	 * : Request for specific reference data types in theresponse.Provide one reference data type or a comma-delimited string of reference data types. Default returns all available referece data types.
	 *
	 * [--languages=<languages>]
	 * : A comma-delimited string of languageIds. For the reference data that may specify a language, the response will only return translations that match the requested languageIds. Default returns all existing translations. Refer to resources for supported resources that support a specific language.
	 *
	 * [--program_ids=<programIds>]
	 * : Returns reference data for approved and active offers (includes expired offers) that belong to the specified program. Two additional responses of "promoting_countries" and "redemption_countries" are returned in this request.
	 *
	 * [--vdc_debug]
	 * : Include VDC debugging output.  Note that this is different than WP_CLI's --debug flag.
	 *
	 * [--ssl_verify]
	 * : Enable SSL verification.  Default true.  To disable verification, use --no-ssl_verify.
	 *
	 * @since 0.1.0
	 */
	function ref( $args, $assoc_args ) {
		$default_assoc_args = array(
			'resources' => null,
			'languages' => null,
			'program_ids' => null,
		);

		$assoc_args = wp_parse_args( $assoc_args, array_merge( $default_assoc_args, $this->common_assoc_args ) );

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

		$this->api = new Reference_Data_API();
	}
}
