<?php
require 'convert_city_to_coordinates.php';
require 'fetch_solarcycle_from_city.php';

if(isset($_GET['city']) && $_GET['city'] != '') {
    // Get city from the form and sanitize it to prevent code injection
    $city = ucwords(filter_input(INPUT_GET, 'city', FILTER_SANITIZE_STRING));
    // Get date from the form and sanitize it to prevent code injection
    $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);

    $coordinates = getCoordinates($city);

    // Check if the city was found
    if ($coordinates !== null) {
        if ($date !== null && $date != '') {
            // Get solar cycle data for the date provided 
            $solarCycleData = getSolarCycle($coordinates['lat'], $coordinates['lon'], $date);
        } else {
            // Get solar cycle data for the current week if no date was provided
            $solarCycleData = getSolarCycleForWeek($coordinates['lat'], $coordinates['lon']);
        }        

        if ($solarCycleData !== null) {
            // Display solar cycle data for the week or the date provided 
            echo "<h2>Solar cycle for $city</h2>";
            foreach ($solarCycleData as $day) {
                // Convert UTC time to local time using the timezone provided by Google Maps Timezone API
                $sunriseLocal = getLocalTime($day['sunrise'], $coordinates['timezone']);
                $sunsetLocal = getLocalTime($day['sunset'], $coordinates['timezone']);
                echo "<h3>{$day['date']}</h3>";
                echo "The sun goes up at $sunriseLocal and goes down at $sunsetLocal<br>";
                // echo "the sun in $city goes up at $sunriseLocal and goes down at $sunsetLocal<br>";
            }
        } else {
            echo "Failed to get solar cycle data :(";
        }
    } else {
        echo "City not found! Please try again";
    }
}
?>