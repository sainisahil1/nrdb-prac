<?php

require 'vendor/autoload.php';
use MongoDB\Client;

// Connect to MongoDB
$mongoClient = new Client("mongodb://localhost:27017");
$db = $mongoClient->selectDatabase("CountryIPv4_" . date("dmY"));
$collection = $db->selectCollection("countries");

// File paths
$locationFile = "GeoLite2-Country-Locations-en.csv";
$ipv4File = "GeoLite2-Country-Blocks-IPv4.csv";

// Step 1: Read Country Data
$countryData = [];
if (($handle = fopen($locationFile, "r")) !== FALSE) {
    $headers = fgetcsv($handle); // Read headers
    while (($row = fgetcsv($handle)) !== FALSE) {
        $country_id = $row[0]; // GeoName ID

        if (!isset($countryData[$country_id])) {

            //change this based on csv format
            $countryData[$country_id] = [
                "country_id" => (int)$row[0],
                "iso_code" => $row[4],
                "country_name" => $row[5],
                "continent" => $row[1],
                "continent_code" => $row[2],
                "geoname_id" => (int)$row[0],
                "is_in_european_union" => $row[7] === "1",
                "ipv4_networks" => [] // Initialize empty array
            ];
        }
    }
    fclose($handle);
}

// Step 2: Read IPv4 Data & Group Networks by Country
if (($handle = fopen($ipv4File, "r")) !== FALSE) {
    $headers = fgetcsv($handle); // Read headers
    while (($row = fgetcsv($handle)) !== FALSE) {
        $network = $row[0];    // IPv4 Network
        $geoname_id = $row[1]; // GeoName ID

        if (isset($countryData[$geoname_id])) {
            $countryData[$geoname_id]["ipv4_networks"][] = $network;
        }
    }
    fclose($handle);
}

// Step 3: Delete old data & Insert new data
$collection->deleteMany([]); // Clear collection to prevent duplicates
$collection->insertMany(array_values($countryData)); // Insert all countries at once

echo "Data successfully inserted using insertMany.\n";

?>
