<?php
/**
 *   index.php F3 router for the BAS Business Portfolio
 *
 *  @authors Noah Lanctot, Mehak Saini, Braedon Billingsley, Will Castillo
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/

require_once('vendor/autoload.php');

// Turn on error reporting and start the PHP session
session_start([
    'cookie_lifetime' => 86400, //expires in a day
]);

// In production scenario be sure to log errors instead of display
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Declare constants for status codes
const HTTP_OK = 200;
const HTTP_BAD_REQUEST = 400;
const HTTP_INTERNAL_SERVER_ERROR = 500;

// Instantiate Fat-free framework (F3), and new Controller F3 object
$f3 = Base::instance();
$con = new Controller($f3);

// Default route to "home.html" view
$f3->route('GET /', function () use ($con)
{
    $con->renderHome();
});

// Route to handle "request_access.html" submission
$f3->route('POST /request-access', function() use($con)
{
    $email = $_POST['email'];
    $message = $_POST['message'];
    $con->processAccessRequest($email, $message);
});

// Route to "request_access.html" view
$f3->route('GET /request-access', function () use ($con)
{
    $con->renderRequestAccess();
});

// Route to "access_code.html" view
$f3->route('GET /access-code', function () use ($con)
{
    $con->renderAccessCode();
});

// Route to handle access code submission
$f3->route('POST /access-code', function() use ($con)
{
    $accessCode = $_POST['accessCode'];
    $con->processAccessCode($accessCode);
});

// Route to handle access request verification
$f3->route('GET /verify-access-request', function() use ($con)
{
    $con->verifyAccessRequest();
});

// Route to "login.html" view
$f3->route('GET /login', function() use ($con)
{
    $con->renderLogin();
});

// Route to process login submission
$f3->route('POST /login', function() use ($con)
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $con->processLogin($username, $password);
});

// Route to "register.html" view
$f3->route('GET /register', function() use ($con)
{
    $con->renderRegister();
});

// Route to process register submission
$f3->route('POST /register', function() use ($con)
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $con->processRegister($username, $email, $password, $confirmPassword);
});

// Route to "timeline.html" view
$f3->route('GET /timeline', function () use ($con)
{
    $con->renderTimeline();
});

// Route to handle item form submission
$f3->route('POST /item', function () use ($con)
{
    $con->processItem();
});

// Route to "item.html" view
$f3->route('GET /item', function () use ($con)
{
    $con->renderItem();
});

// Route to fetch all items to json
$f3->route('GET /get-items', function() use ($con)
{
    $con->processGetItems();
});

// Route to "task.html" view
$f3->route('GET /task', function () use ($con)
{
    $con->renderTask();
});

// Route to handle user logout and session clearing
$f3->route('GET /logout', function() use ($con)
{
    $con->logout();
});

// Run the Fat-Free instance
$f3->run();



