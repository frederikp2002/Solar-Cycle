<?php
function getSolarCycle($lat, $lon, $date = null) {
    // If no date is provided, use the current date
    if ($date === null) {
        $date = date('Y-m-d');
    }

    // Request URL for Sunrise-Sunset API
    $sunriseSunsetUrl = "https://api.sunrise-sunset.org/json?lat=$lat&lng=$lon&date=$date";

    // Fetch solar cycle data
    $sunriseSunsetResponse = file_get_contents($sunriseSunsetUrl);

    if ($sunriseSunsetResponse === false) {
        exit('Failed to get solar cycle data :(');
    }

    $sunriseSunsetData = json_decode($sunriseSunsetResponse);

    // Check if the data was fetched successfully
    if (isset($sunriseSunsetData->results)) {
        $sunrise = $sunriseSunsetData->results->sunrise;
        $sunset = $sunriseSunsetData->results->sunset;

        $solarCycleData = [
            [
                'date' => $date,
                'sunrise' => $sunrise,
                'sunset' => $sunset
            ]
        ];

        return $solarCycleData;
    }

    return null;
}

function getSolarCycleForWeek($lat, $lon) {
    // Get current date
    $currentDate = new DateTime();

    // Get the next Sunday date
    $nextSunday = new DateTime('next Sunday');
    $nextSunday->setTime(23, 59, 59);  // Set time to the end of the day

    $solarCycleData = [];

    // Loop through the days of the week until the next Sunday
    while ($currentDate <= $nextSunday) {
        // Fetch solar cycle data for this date
        $solarCycle = getSolarCycle($lat, $lon, $currentDate->format('Y-m-d'));

        if ($solarCycle !== null) {
            // Add the solar cycle data to the array
            $solarCycleData[] = $solarCycle[0];
        }

        // Go to the next date
        $currentDate->modify('+1 day');
    }

    return $solarCycleData;
}

?>
