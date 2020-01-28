<?php

namespace SHC\VDC;

defined( 'ABSPATH' ) || die;

/**
 * Visa Card Eligibility Service API.
 *
 * Visa Card Eligibility Service REST API.
 *
 * For more information about this API, see https://developer.visa.com/capabilities/vces/reference.
 *
 * @since 0.1.0
 */
class Eligibility_Command extends VDC_Command {
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
		'vendor_unique_id'                 => 'vendorUniqueId',
		'request_time_stamp'               => 'requestTimeStamp',
		'correlation_id'                   => 'correlationId',
		'extended_data'                    => 'extendedData',
		'number_of_additional_redemptions' => 'numberOfAdditionalRedemptions',
		'expiration_date'                  => 'expirationDate',
	);

	/**
	 * Resource Operation to validate if a card is eligible or not.
	 *
	 * Returns information of the card products eligibility against a program and corresponding card details if configured for that program.
	 *
	 * For more information about this endpoint, see https://developer.visa.com/capabilities/vces/reference#vces__visa_card_eligibility_service__v1__validate_card_eligibility
	 *
	 * <permanentAccountNumber>
	 * : Payment card number to be used against the Vendor Unique ID.
	 *
	 * [--vendor_unique_id=<vendorUniqueId>]
	 * : Vendor Unique ID.  If not supplied, the value from the default value from the Project_Config object will be used.
	 *
	 * [--request_time_stamp=<requestTimeStamp>]
	 * : Time stamp of the request and in GMT format MM/dd/yyyy hh:mm:ss a.  If not supplied, the current date/time will be used.
	 *
	 * [--correlation_id=<correlationId>]
	 * : unique string of characters.  If not supplied, a random string of 15 characters will be used.
	 *
	 * [--extended_data=<extendedData>]
	 * : additional data.
	 *
	 * [--number_of_additional_redemptions=<numberOfAdditionalRedemptions>]
	 * : Number of additional redemptions.
	 *
	 * [--expiration_date=<expirationDate>]
	 * : Expiration date of the permanentAccountNumber in format MM/YY or MM/YYYY.
	 *
	 * [--vdc_debug]
	 * : Include VDC debugging output.  Note that this is different than WP_CLI's --debug flag.
	 *
	 * [--ssl_verify]
	 * : Enable SSL verification.  Default true.  To disable verification, use --no-ssl_verify.
	 *
	 * @since 0.1.0
	 */
	function validate( $args, $assoc_args ) {
		$default_assoc_args = array(
			'vendor_unique_id'                 => Project_Config::getDefaultVendorUniqueId(),
			'request_time_stamp'               => date( 'n/d/Y h:i:s A' ),
			'correlation_id'                   => $this->generate_correlationid(),
			'extended_data'                    => null,
			'number_of_additional_redemptions' => null,
			'expiration_date'                  => null,
		);
		$assoc_args = wp_parse_args( $assoc_args, wp_parse_args( $default_assoc_args, $this->common_assoc_args ) );

		$this->setup_config( $assoc_args );
		unset( $assoc_args['vdc_debug'], $assoc_args['verify'] );

		if ( $assoc_args['expiration_date'] ) {
			list( $month, $year ) = explode( '/', $assoc_args['expiration_date'] );

			$assoc_args['expiration_date'] = array( 'Month' => $month, 'Year' => $year );
		}

		$postData                           = $this->map_params( $assoc_args );
		$postData['permanentAccountNumber'] = array_shift( $args );
		$this->call_api( __FUNCTION__, $postData );

		return;
	}

	/**
	 * Resource Operation to validate if a card is eligible or not.
	 *
	 * Returns information of the card products eligibility against a program and corresponding card details if configured.
	 *
	 * For more information about this endpoint, see https://developer.visa.com/capabilities/vces/reference#vces__visa_card_eligibility_service__v1__prepay
	 *
	 * <permanentAccountNumber>
	 * : Payment card number to be used against the Vendor Unique ID.
	 *
	 * [--vendor_unique_id=<vendorUniqueId>]
	 * : Vendor Unique ID.  If not supplied, the value from the default value from the Project_Config object will be used.
	 *
	 * [--request_time_stamp=<requestTimeStamp>]
	 * : Time stamp of the request and in GMT format MM/dd/yyyy hh:mm:ss a.  If not supplied, the current date/time will be used.
	 *
	 * --correlation_id=<correlationId>
	 * : unique string of characters.
	 *
	 * [--vdc_debug]
	 * : Include VDC debugging output.  Note that this is different than WP_CLI's --debug flag.
	 *
	 * [--ssl_verify]
	 * : Enable SSL verification.  Default true.  To disable verification, use --no-ssl_verify.
	 *
	 * @since 0.1.0
	 */
	function prepay( $args, $assoc_args ) {
		$default_assoc_args = array(
			'vendor_unique_id'                 => Project_Config::getDefaultVendorUniqueId(),
			'request_time_stamp'               => date( 'n/d/Y h:i:s A' ),
		);
		$assoc_args = wp_parse_args( $assoc_args, wp_parse_args( $default_assoc_args, $this->common_assoc_args ) );

		$this->setup_config( $assoc_args );
		unset( $assoc_args['vdc_debug'], $assoc_args['verify'] );

		$postData                           = $this->map_params( $assoc_args );
		$postData['permanentAccountNumber'] = array_shift( $args );
		$this->call_api( __FUNCTION__, $postData );

		return;
	}

	/**
	 * Resource Operation to redeem a validated card.
	 *
	 * Returns information of the card products eligibility against a program and corresponding card details if configured.
	 *
	 * For more information about this endpoint, see https://developer.visa.com/capabilities/vces/reference#vces__visa_card_eligibility_service__v1__redeem_offer
	 *
	 * <permanentAccountNumber>
	 * : Payment card number to be used against the Vendor Unique ID.
	 *
	 * [--vendor_unique_id=<vendorUniqueId>]
	 * : Vendor Unique ID.  If not supplied, the value from the default value from the Project_Config object will be used.
	 *
	 * [--request_time_stamp=<requestTimeStamp>]
	 * : Time stamp of the request and in GMT format MM/dd/yyyy hh:mm:ss a.  If not supplied, the current date/time will be used.
	 *
	 * --correlation_id=<correlationId>
	 * : unique string of characters.
	 *
	 * [--mode=<mode>]
	 * : Type of redemption request.
	 *
	 * [--vdc_debug]
	 * : Include VDC debugging output.  Note that this is different than WP_CLI's --debug flag.
	 *
	 * [--ssl_verify]
	 * : Enable SSL verification.  Default true.  To disable verification, use --no-ssl_verify.
	 *
	 * @since 0.1.0
	 */
	function redeem( $args, $assoc_args ) {
		$default_assoc_args = array(
			'vendor_unique_id'   => Project_Config::getDefaultVendorUniqueId(),
			'request_time_stamp' => date( 'n/d/Y h:i:s A' ),
			'mode'               => null,
		);
		$assoc_args = wp_parse_args( $assoc_args, wp_parse_args( $default_assoc_args, $this->common_assoc_args ) );

		$this->setup_config( $assoc_args );
		unset( $assoc_args['vdc_debug'], $assoc_args['verify'] );

		$postData                           = $this->map_params( $assoc_args );
		$postData['permanentAccountNumber'] = array_shift( $args );
		$this->call_api( __FUNCTION__, $postData );

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

		$this->api = new Card_Elibility_Service_API();
	}

	/**
	 * Generate a unique correlation ID.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	protected function generate_correlationid() {
		$id = wp_generate_password( 15, false, false );

		return $id;
	}
}
