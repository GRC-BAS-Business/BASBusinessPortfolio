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
session_start([
    'cookie_lifetime' => 86400, //expires in a day
]);
// in production scenario be sure to log errors instead of display
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Instantiate Fat-free framework (F3), and new Controller F3 object
$f3 = Base::instance();
$con = new Controller($f3);
$ajax = new AJAX($f3);

// Default route to "home.html" view
$f3->route('GET /', function () use ($con)
{
    $con->renderHome();
});

$f3->route('POST /request-access', function($f3) use($con, $ajax) {
    $email = $_POST['email'];
    $message = $_POST['message'];
    if ($f3->get('AJAX')) {
        $con->processAccessRequest($email, $message);
    } else {
        $ajax->processAccessRequestWithRedirect($email, $message);
    }
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

// Submit Access Code
$f3->route('POST /access-code', function() use($con) {
    $accessCode = $_POST['accessCode'];
    $con->verifyAccessCode($accessCode);
});

// Route to handle login form submission
$f3->route('POST /login', function () use ($con)
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $con->processLogin($username, $password);
});

// Route to "login.html" view
$f3->route('GET /login', function () use ($con)
{
    $con->renderLogin();
});

// Route to "timeline.html" view
$f3->route('GET /timeline', function () use ($con)
{
    $con->renderTimeline();
});

// Route to handle item form submission
$f3->route('POST /item', function () use ($con)
{
    $con->createItem();
});

// Route to "item.html" view
$f3->route('GET /item', function () use ($con)
{
    $con->renderItem();
});

$f3->route('GET /get-items', function() {
    header('Content-Type: application/json');

    // Call the function that fetches all items
    $itemTypes = Item::getItems();

    // Return the result as a JSON string
    echo json_encode($itemTypes);
});

// Route to "task.html" view
$f3->route('GET /task', function () use ($con)
{
    $con->renderTask();
});

// Run the Fat-Free instance
$f3->run();



