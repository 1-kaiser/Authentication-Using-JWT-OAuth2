<?php
require_once 'vendor/autoload.php';
require_once 'config.php';

if (isset($_SESSION['userToken'])) {
    header('Location: welcome.php');
} else {

    echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";

}
?>