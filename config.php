<?php
// // if (!defined('DB_SERVER'))
//     define('DB_SERVER', 'localhost');
// // if (!defined('DB_USERNAME'))
//     define('DB_USERNAME', 'root');
// // if (!defined('DB_PASSWORD'))
// define('DB_PASSWORD', '');
// // if (!defined('DB_NAME'))
// define('DB_NAME', 'water_billing');

global $link;
$link = mysqli_connect('localhost', 'root', '', 'water_billing');

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
