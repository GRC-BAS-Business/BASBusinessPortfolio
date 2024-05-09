<?php
/**
 *   index.php F3 router for the BAS Business Portfolio Fat-Free Framework Routes
 *
 *  @authors Noah Lanctot, Mehak Saini, Braedon Billingsley, Will Castillo
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/

require_once('vendor/autoload.php');

// Turn on error reporting and start the PHP session
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Instantiate Fat-free framework (F3), and new Controller F3 object, and RequestValidator
$f3 = Base::instance();
$con = new Controller($f3);

// Default route to "Home" page
$f3->route('GET /', function () use ($con)
{
    $con->renderHome();
});

// Run the Fat-Free instance
$f3->run();



