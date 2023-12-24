<?php
require_once 'config.php';

// Authenticate Code From Google OAuth Flow
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);
  
    // Getting the Profile Information
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $userInfo = [
        'email' => $google_account_info['email'],
        'firstName' => $google_account_info['giveName'],
        'familyName' => $google_account_info['familyName'],
        'gender' => $google_account_info['gender'],
        'fullName' => $google_account_info['name'],
        'picture' => $google_account_info['picture'],
        'verifiedEmail' => $google_account_info['verifiedEmail'],
        'token' => $google_account_info['token']
    ];
  
    $query = mysqli_query($conn, "SELECT * FROM login WHERE email = '{$userInfo['email']}'");
    if (mysqli_num_rows($query) > 0) {

        $userInfo = mysqli_fetch_assoc($query);
        $token = $userInfo['token'];

    } else {
        $insertToDatabase = mysqli_query($conn, "INSERT INTO login (email, firstName, lastName, gender, fullName, picture, verifiedEmail, token) 
        VALUES ('{$userInfo['email']}', '{$userInfo['firstName']}', '{$userInfo['familyName']}', '{$userInfo['gender']}', '{$userInfo['fullName']}', '{$userInfo['picture']}', '{$userInfo['verifiedEmail']}', '{$userInfo['token']}')");

        if ($insertToDatabase) {
            echo "Users inserted successfully <br/>";
            $token = $userInfo['token'];
        } else {
            echo "Something went wrong";
        }
    } 

    $_SESSION['userToken'] = $token;

} else {

    if (!isset($_SESSION['userToken'])) {
        header('Location: index.php');
        die();
    }

    $query = mysqli_query($conn, "SELECT * FROM login WHERE token = '{$_SESSION['userToken']}'");
    if (mysqli_num_rows($query) > 0) {

        $userInfo = mysqli_fetch_assoc($query);

    }
}


// Client ID
// 371055177081-br3sch9ldpems078p4tjd4gme11iip24.apps.googleusercontent.com

// Client Secret
// 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

// Redirect URL
// http://localhost/3rd%20Year/JWT/welcome.php


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JWT</title>
</head>
<body>

    <img src="<?= $userInfo['picture']?>" alt="">

    <ul>
        <li>Full Name: <?= $userInfo['fullName']?></li>
        <a href="logout.php">Logout</a>
    </ul>

</body>
</html>