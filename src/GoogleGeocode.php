<?php
/* SVN FILE: $Id$ */
/**
 * GoGeocode object for use with Google's Geocoding API
 *
 * Copyright (c) 2008.
 * Licensed under the MIT License.
 * See LICENSE for detailed information.
 * For credits and origins, see AUTHORS.
 *
 * PHP 5
 *
 * @filesource
 * @version             $Revision$
 * @modifiedby          $LastChangedBy$
 * @lastmodified        $Date$
 * @license             http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */

require_once('BaseGeocode.php');

/**
 * Geocoder object for use with Google's geocoding API
 */
class GoogleGeocode extends BaseGeocode
{
	/*
	 * Status code information grokked from:
	 * http://code.google.com/apis/maps/documentation/reference.html#GGeoStatusCode
	 */

	/**
	 * Status Code:
	 * No errors occurred; the address was successfully parsed and its geocode has been returned.
	 * @var int
	 * @access public
	 */
	const SUCCESS = "OK";

	/**
	 * Status Code:
	 * HTTP Status Code 404 Not Found
	 * @var int
	 * @access public
	 */
	const NOT_FOUND = "ZERO_RESULTS";

	/**
	 * Status Code:
	 * A geocoding or directions request could not be successfully processed,
	 * yet the exact reason for the failure is not known.
	 * @var int
	 * @access public
	 */
	const SERVER_ERROR = "UNKNOWN_ERROR";

	/**
	 * Status Code:
	 * The HTTP q parameter was either missing or had no value.
	 * For geocoding requests, this means that an empty address was specified as input.
	 * For directions requests, this means that no query was specified in the input.
	 * @var int
	 * @access public
	 */
	const MISSING_QUERY = "INVALID_REQUEST";

	/**
	 * Status Code:
	 * Synonym for MISSING_QUERY.
	 * @var int
	 * @access public
	 */
	const MISSING_ADDRESS = "INVALID_REQUEST";

	/**
	 * Status Code:
	 * No corresponding geographic location could be found for the specified address.
	 * This may be due to the fact that the address is relatively new, or it may be incorrect.
	 * @var int
	 * @access public
	 */
	const UNKNOWN_ADDRESS = "ZERO_RESULTS";

	/**
	 * Status Code:
	 * The given key has gone over the requests limit in the 24 hour period.
	 * @var int
	 * @access public
	 */
	const TOO_MANY_QUERIES = "OVER_QUERY_LIMIT";

	/**
	 * Geocode the provided API. See BaseGeocode::geocode for detailed information
	 * about this function's return type.
	 *
	 * @param string $address The string address to retrieve geocode information about
	 * @return array An empty array on server not found. Otherwise an array of geocoded location information.
	 */
		public function geocode( $address )
	{
		$retVal = array();
		$url = "http://maps.google.com/maps/geo?q=";
		$url = "http://maps.googleapis.com/maps/api/geocode/xml?address=";
		$url .= urlencode( $address ) . "&sensor=false";

		$file = $this->loadXML( $url );

		if( empty( $file ) ) {
			return $retVal;
		}

		$retVal['Response'] = array( 
			'Status' => $file['response'],
			'Request' => 'geo'
			);

		if( $file['response'] == '200' ) {
			$xml = new SimpleXMLElement( $file['contents'] );
			//Now that we have the google request, and we succeeded in getting a response
			//from the server, lets replace our response portion with the google response
			$retVal['Response']['Status'] = (string)$xml->status;
			$retVal['Response']['Request'] = (string)$xml->result->type;

			if( $xml && $retVal['Response']['Status'] == 'OK' )
			{
				$retVal['Geometry']['Latitude']= (double)$xml->result->geometry->location->lat;
				$retVal['Geometry']['Longitude'] = (double)$xml->result->geometry->location->lng;
			}
		}
		return $retVal;
	}
}

?>
