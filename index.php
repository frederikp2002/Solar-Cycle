<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Solar Cycle</h1>
    <form action="" method="GET">
        <label for="city">Enter a city:</label><br>
        <input type="text" id="city" name="city" required><br>
        <label for="date">Select a date:</label><br>
        <input type="date" id="date" name="date"><br>
        <input type="submit" value="Get Sunrise and Sunset">
    </form>
    <div class="result">
        <?php
        require 'backend/solarcycle.php';
        ?>
    </div>
</body>
</html>
