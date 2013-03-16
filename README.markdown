Introduction
============

My fork of GoGeocode is an update of the original PHP library designed to work with the Google Maps V3 API. Making a geolocation requestion does not require an API key (within Google's convenience request limit of 2500 per day) and the return values, in PHP array form, are much simpler: latitude and longitude.

Requirements
============
  * PHP 5
  * SimpleXML Extension

Instructions
============

To use GoGeocode to geocode simply declare the GoGeocode object of your choice and use the geocode function.

Example (Geocode an address using Google's geocoding service)
-------------------------------------------------------------


    require('GoogleGeocode.php');
    $geo = new GoogleGeocode();
    $result = $geo->geocode( "13 Oak Drive, Hamilton, NY, 13346" );
    print_r( $result );

Which would output:

    Array(
        'Response' => array(
            'Status' => 'OK',
            'Request' => 'postal_code'
        ),
        'Geometry' => array(
            'Latitude' => (float) 42.8194512,
            'Longitude' => (float) -75.5363587
        )
    )