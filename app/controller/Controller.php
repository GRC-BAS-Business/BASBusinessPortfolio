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
     * Checks if the user is logged in, otherwise reroutes to the login page.
     *
     * @return void
     */
    public function checkLogin(): void
    {
        if (!$this->_f3->get('SESSION.loggedin')) {
            $this->_f3->reroute('/login');
        }
    }

    /**
     * Renders the home.html view.
     *
     * @return void
     */
    function renderHome(): void
    {
        $this->checkLogin();
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/home.html');
    }

    /**
     * Renders the request_access.html view.
     *
     * @return void
     */
    function renderRequestAccess(): void
    {
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/request_access.html');
    }

    /**
     * Processes an access request by sending an email to the admin with the provided email and message.
     *
     * @param string $email The email of the requester.
     * @param string $message The message from the requester.
     * @return void
     */
    function processAccessRequest(string $email, string $message): void
    {
        $email = Validate::sanitizeString($email);
        $message = Validate::sanitizeString($message);

        $response = [];

        if (!Validate::isValidEmail($email)) {
            $this->_f3->set('SESSION.error', $this->interpretError(Validate::INVALID_EMAIL));
        } else if (!$message) {
            $this->_f3->set('SESSION.error', $this->interpretError(Validate::INVALID_STRING));
        } else {
            $response = Access::createAccessCodeAndMailToStudent($email);

            if ($response == Access::DUPLICATE_EMAIL) {
                $this->_f3->set('SESSION.error', $this->interpretError(Access::DUPLICATE_EMAIL));
            } elseif ($response == Validate::REQUEST_SUCCESS) {
                $this->_f3->set('SESSION.success', $this->interpretError(Validate::REQUEST_SUCCESS));
            }
        }
        echo json_encode($response);
    }

    /**
     * Renders the access_code.html view.
     *
     * @return void
     */
    function renderAccessCode(): void {
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/access_code.html');
    }

    /**
     * Verifies the access code provided by the user.
     *
     * @param string $accessCode The access code to be verified.
     * @return void
     */
    function verifyAccessCode(string $accessCode): void
    {
        // sanitize and validate $accessCode
        $accessCode = Validate::sanitizeString($accessCode);
        if (!Validate::isValidAccessCode($accessCode)) {
            // Handle invalid accessCode scenario...
            $this->_f3->set('SESSION.error', $this->interpretError(5));
            $this->_f3->reroute('access-code');
        }


        // compares the provided access code with the one stored in the database.
        if (Access::checkAccessCode($accessCode)) {
            $this->_f3->set('SESSION.isVerified', true);

            // allow the user to sign up
            $this->_f3->reroute('login');
        } else {
            $this->_f3->set('SESSION.error', $this->interpretError(5));
            $this->_f3->reroute('access-code');
        }
    }

    function interpretError($result): string
    {
        return match ($result) {
            Validate::INVALID_EMAIL => 'Please enter a valid email address',
            Validate::INVALID_NAME => 'Please enter a valid name',
            Validate::INCORRECT_PASSWORD => 'The password you entered is incorrect!',
            Validate::INCORRECT_USERNAME => 'The username you entered is incorrect!',
            Validate::INCORRECT_ACCESS_CODE => 'The access code you entered is incorrect!',
            Validate::INVALID_STRING => 'Please enter a valid string',
            Validate::REQUEST_SUCCESS => 'Access request sent successfully!',
            Validate::DUPLICATE_EMAIL => 'This email already exists',
            default => 'No match!',
        };
    }

    /**
     * Renders the login.html view.
     *
     * @return void
     */
    function renderLogin(): void
    {
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/login.html');
    }

    /**
     * Processes the login form submission.
     *
     * @param string $username The username provided by the user.
     * @param string $password The password provided by the user.
     * @return void
     */
    function processLogin(string $username, string $password): void
    {
        $username = Validate::sanitizeString($username);
        $password = Validate::sanitizeString($password);

        if (Validate::isValidLogin($username, $password)) {
            $authResult = UserAccount::authenticateUser($username, $password);

            if ($authResult) {
                $this->_f3->set('SESSION.loggedin', true);
                $this->_f3->set('SESSION.username', $username);
                $this->_f3->reroute('/timeline');
            } else {
                $this->_f3->set('SESSION.error', 'Invalid username or password');
                $this->_f3->reroute('/login');
            }
        } else {
            $this->_f3->set('SESSION.error', 'Invalid input');
            $this->_f3->reroute('/login');
        }
    }

    /**
     * Renders the registration page.
     *
     * @return void
     */
    function renderRegister(): void
    {
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/register.html');
    }

    /**
     * Processes the registration form submission.
     *
     * @param string $username The username provided by the user.
     * @param string $email The email provided by the user.
     * @param string $password The password provided by the user.
     * @param string $confirmPassword The password confirmation provided by the user.
     * @return void
     */
    function processRegister(string $username, string $email, string $password, string $confirmPassword): void
    {
        $username = Validate::sanitizeString($username);
        $email = Validate::sanitizeString($email);
        $password = Validate::sanitizeString($password);
        $confirmPassword = Validate::sanitizeString($confirmPassword);

        if (Validate::isValidRegistration($username, $email, $password, $confirmPassword)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $registrationResult = UserAccount::createUser($username, $email, $hashedPassword);

            if ($registrationResult) {
                $this->_f3->set('SESSION.success', 'Registration successful! Please log in.');
                $this->_f3->reroute('/login');
            } else {
                $this->_f3->set('SESSION.error', 'Registration error. Please try again.');
                $this->_f3->reroute('/register');
            }
        } else {
            $this->_f3->set('SESSION.error', 'Invalid input. Please correct the errors and try again.');
            $this->_f3->reroute('/register');
        }
    }

    function renderTimeline(): void
    {
        $this->checkLogin();
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/timeline.html');
    }

    function renderItem(): void
    {
        $this->checkLogin();
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/item.html');
    }

    public function processItem(): void
    {
        $this->checkLogin();
        $createdDate = new DateTime();
        $itemDescription = $_POST['itemDescription'];
        $title = $_POST['title'];
        $itemType = $_POST['itemType'];
        $itemImage = $_POST['itemImage'];

        $item = new Item($createdDate, $itemDescription, '', $itemType, $title);
        $item->saveItem();
    }


    public function createTimeline(): void {
        // TODO Create 1 timeline object in Student account upon account creation.
    }

    function renderTask(): void

    {
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/task.html');
    }

    /**
     * Clears session error and success messages.
     *
     * @return void
     */
    public function clearSessionMessages(): void
    {
        $this->_f3->clear('SESSION.error');
        $this->_f3->clear('SESSION.success');
    }

    /**
     * Logs the user out by clearing the session and redirecting to the login page.
     *
     * @return void
     */
    function logout(): void
    {
        $this->_f3->clear('SESSION');
        session_destroy();
        $this->_f3->reroute('/login');
    }
}