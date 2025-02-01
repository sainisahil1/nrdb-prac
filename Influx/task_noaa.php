<?php

require '/Users/sahilsaini/vendor/autoload.php';
$client = new InfluxDB\Client('localhost', 8086);
$database = $client->selectDB('NOAA_water_database');
$result = $database->query("SELECT * FROM h2o_temperature where time > now() - 1h");
echo json_encode($result->getPoints());

echo '<br><br>';

$points = array(
    new InfluxDB\Point(
        'h2o_temperature',
        null,
        ['location'=>'santa_monica'],
        ['degrees'=>62]
    )
);
$database->writePoints($points, InfluxDB\Database::PRECISION_SECONDS);

echo '<br><br>';

echo 'after adding';

$result = $database->query("SELECT * FROM h2o_temperature where time > now() - 1h");
echo json_encode($result->getPoints());
