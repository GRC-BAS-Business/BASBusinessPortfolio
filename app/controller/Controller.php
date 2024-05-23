<?php
/**
 *  This is the Controller class for BAS Business Portfolio application
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
class Controller
{
    private object $_f3;
    private PDO $_database;

    /**
     * Constructs a controller object.
     *
     * @param mixed $f3 The global $f3 hive to be assigned to the $_f3 parameter.
     * @return void
     */
    public function __construct(object $f3)
    {
        $this->_f3 = $f3;
        $this->_database = Database::getConnection();
    }

    /**
     * Renders the home.html view.
     *
     * @return void
     */
    function renderHome(): void
    {
        $view = new Template();
        echo $view->render('app/view/home.html');
    }

    /**
     * Renders the login.html view.
     *
     * @return void
     */
    function renderLogin(): void
    {
        $view = new Template();
        echo $view->render('app/view/login.html');
    }

    function processLogin($username, $password): void
    {
        if (!UserAccount::validateLogin($username, $password)) {
            // Set error message and reroute to login page
            $_SESSION['login_error'] = "Invalid username or password format.";
            $this->_f3->reroute('login');
        }

        if (!UserAccount::authenticateUser($username, $password)) {
            // Set error message and reroute to login page
            $_SESSION['login_error'] = "Invalid username or password.";
            $this->_f3->reroute('login');
        }

        // The user is authenticated - reroute to home
        $this->_f3->reroute('');
    }

    /**
     * Renders the timeline.html view.
     *
     * @return void
     */
    function renderTimeline(): void
    {
        $view = new Template();
        echo $view->render('app/view/timeline.html');
    }

    /**
     * Renders the item.html view.
     *
     * @return void
     */
    function renderItem(): void
    {
        $view = new Template();
        echo $view->render('app/view/item.html');
    }

    // TODO add itemID to portfolioID and add PortfolioID to studentID
    function createItem(): void
    {
        $portfolioID = $_SESSION['portfolioID'];
        $createdDate = new DateTime();
        $itemType = $_SESSION['itemType'];
        $itemDescription = $_POST['itemDescription'];
        $title = $_POST['title'];

        $item = new Item($createdDate, $itemDescription, '', $itemType, $title);
        $item->save();
    }
}