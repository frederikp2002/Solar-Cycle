<?php
function getCoordinates($city) {
    $env = parse_ini_file('.env');
    $google_ApiKey = $env['GOOGLE_APIKEY']; 

    // Request URL for Google Maps Geocoding API
    $geocodingUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($city) . "&key=$google_ApiKey";

    // Get coordinates
    $geocodingResponse = file_get_contents($geocodingUrl);

    if ($geocodingResponse === false) {
        exit('Failed to get geocoding data');
    }

    $geocodingData = json_decode($geocodingResponse);

    // Check if the city was found
    if (isset($geocodingData->results[0])) {
        $lat = $geocodingData->results[0]->geometry->location->lat;
        $lon = $geocodingData->results[0]->geometry->location->lng;

        // Get timezone
        $timezoneUrl = "https://maps.googleapis.com/maps/api/timezone/json?location=$lat,$lon&timestamp=" . time() . "&key=$google_ApiKey";
        $timezoneResponse = file_get_contents($timezoneUrl);
        $timezoneData = json_decode($timezoneResponse);
        $timezone = $timezoneData->timeZoneId;

        return ['lat' => $lat, 'lon' => $lon, 'timezone' => $timezone];
    }

    return null;
}
function getLocalTime($utcTime, $timezone) {
    // Convert UTC time to local time using the timezone provided by Google's API
    $date = new DateTime($utcTime, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone($timezone));

    return $date->format('H:i');
}

?>