<?php

require_once 'vendor/autoload.php';
session_start();

// init configuration
$clientID = '371055177081-br3sch9ldpems078p4tjd4gme11iip24.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/3rd%20Year/JWT/welcome.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

$client->addScope("email");
$client->addScope("profile");

// Database connection
    $conn = mysqli_connect("localhost", "root", "", "jwt");


?>