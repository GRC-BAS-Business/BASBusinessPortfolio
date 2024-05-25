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

// Instantiate Fat-free framework (F3), and new Controller F3 object
$f3 = Base::instance();
$con = new Controller($f3);

// Default route to "home.html" view
$f3->route('GET /', function () use ($con)
{
    $con->renderHome();
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

<<<<<<< Updated upstream
$f3->route('GET /get-items', function() {
    header('Content-Type: application/json');

    // Call the function that fetches all items
    $itemTypes = Item::getItems();

    // Return the result as a JSON string
    echo json_encode($itemTypes);
});

=======
$f3->route('GET /dashBoard', function () use ($con)
{
    $con->renderDashBoard();

});
>>>>>>> Stashed changes
// Run the Fat-Free instance
$f3->run();



