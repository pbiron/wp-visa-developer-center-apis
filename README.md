# Visa Developer Center APIs #

**Contributors:** [pbiron](https://profiles.wordpress.org/pbiron)  
**Tags:** visa, visa developer center, rest api  
**Requires at least:** 4.6  
**Tested up to:** 5.3.2  
**Stable tag:** 0.1.0  
**License:** GPL-2.0-or-later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

Access Visa Developer Center APIs

## Description ##

This plugin provides PHP classes that allows access to various Visa Developer Center (VDC) APIs.

As of version 0.1.0, 3 VDC APIs are supported:

1. [Visa Card Eligibility Service](https://developer.visa.com/capabilities/vces/docs)
1. [Visa Merchant Offers Resource Center](https://developer.visa.com/capabilities/vmorc/docs)
    1. [Offers Data API](https://developer.visa.com/capabilities/vmorc/reference#vmorc__offers_data_api)
    1. [Reference Data API](https://developer.visa.com/capabilities/vmorc/reference#vmorc__reference_data_api)

Support for additional VDC APIs may or may not be added in future versions.

For example, to access the `visacardeligibilityservices/v1/cardeligibility/validate` endpoint, do something like the following:

```
<?php
use SHC\VDC\Card_Elibility_Service_API;
use SHC\VDC\APIException;

$api      = new Card_Elibility_Service_API();
$postData = array(
	'permenantAccountNumber' => 'The 16 digit credit card number to validate',
	'vendorUniqueId'         => 'The Vendor Unique ID from your project',
	'correlationId'          => 'A unique string to be used in subsequent calls to the "prepay" or "redeem" endpoints', 
);

try {
	// $response_data will be the JSON response from the API. 
	list( $response_data, $http_response_status, $http_response_headers ) = $api->validate( $postData );
	// do something with the $response_data
}
catch ( InvalidArgumentException $ex ) {
	// one or more required query params and/or postData params for the endpoint were not supplied.
	// check $ex->getMessage() for the error message.
}
catch ( APIException $ex ) {
	// check $ex->getMessage() for the error message from the API.
	// In some cases, the API will return a stdClass object with additional error information.  You can
	// access that object with $ex->getResponseBody(). 
}
```

Before you can use the classes provided by this plugin, you will need to register and create a project at the [Visa Developer Center](https://developer.visa.com/).

Once you've created that project,
1. copy the various SSL certs provided in the project to the `includes/certs` directory
1. rename `includes/class-default-project-config.php` to `includes/class-project-config.php`
1. edit `includes/class-project-config.php` and enter the project-specific info (e.g., the `Vendor Unique ID`, `Username`, `Password`, `Password`, `PrivateKeyPath`, etc.).

In addition to classes for accessing these APIs, this plugin also provides [WP-CLI](https://wp-cli.org/) commands for accessing the various endpoints the APIs provide.

For more information about the WP-CLI commands, do `$ wp help vdc`.

**Important note:** I have only tested this with projects that have the "Sandbox" status.  If used in a live project, your mileage may vary. 

## TODOs ##

**Important note:** Because of the todos below, and many other reasons, I reserve the right to release future versions of this plugin that are **not** backwords compatible with this version!
 
### Remove remnants of "Sample" Code ###

The Visa Developer Center provides some sample code for accessing their APIs.  Naturally, I began by experimenting with that code.  As with many codebases of that kind, I found that same code **reallty** hard to work with...and in many cases, buggy!  So, I ditched most of it.
 
There are still 2 remnants of that sample code:

1. The `API_Client` and `ApiException` classes.  These classes are a slight re-working of the equivalent classes in the sample code.
    * I do not like the fact that `API_Client` throws exceptions and think I need to get rid of that
    * I want to replace the `API_Client` class with calls to [wp_remote_request()](https://developer.wordpress.org/reference/functions/wp_remote_request/), etc.
        * I just haven't had time to figure out how to do the [Two-Way SSL](https://developer.visa.com/pages/working-with-visa-apis/two-way-ssl) required by the APIs, using `wp_remote_post()`, etc., but I don't think it will be hard.
1. The `Project_Config` and `Configuration` classes.  Again, these classes are a slight re-working of the equivalent classes in the sample code.
    * I need to figure out a better way to store the project-specific info
    * I just haven't given it enough time to find a better solution

### Storing/Accessing the project certs ###

There should be a more secure way of storing and/or allowing cUrl to access the project certs!

Also, the way things are now, when the next version of this plugin is released, WP's plugin update process will actually nuke your certs, which is not good!  Will have to figure out how to avoid that!

### Arguments to the WP-CLI commands ###

Many of the arguments for various API endpoints are in mixed-case, e.g., `vendorUniqueId`.  Unfortunately, in WP-CLI <= 2.4.0, assoc_arg names are prohibited from containing uppercase characters :-(  Therefore, any API param that contains uppercase characters is "spelled" slightly different when used as an argument to one of the WP-CLI commands.

For example,

* `vendorUniqueId` is spelled `vendor_unique_id`, etc

I've [enquired](https://wordpress.slack.com/archives/C02RP4T41/p1580095495017000) whether a future version of WP-CLI could allow uppercase characters in assoc_arg names.  We'll see how that goes.

## Installation ##

From your WordPress dashboard

1. Go to _Plugins > Add New_ and click on _Upload Plugin_
2. Upload the zip file
3. Activate the plugin

### Build from sources ###

1. clone the global repo to your local machine
2. install node.js and npm ([instructions](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm))
3. install composer ([instructions](https://getcomposer.org/download/))
4. run `npm update`
5. run `composer install`
6. run `grunt build`
    * to build a new release zip
        * run `grunt release`

See `Gruntfile.js` for other grunt tasks that are defined.

## Changelog ##

### 0.1.0 ###

* init commit
