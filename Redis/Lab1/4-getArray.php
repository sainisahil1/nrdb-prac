<?php
$action = $_GET['action'];
$lastname = $_GET['lastname'];

$output = '';
$output .= "action = $action<br>";
$output .= "lastname = $lastname<br>";

echo $output;
