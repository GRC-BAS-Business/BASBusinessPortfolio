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
     * Class Constructor.
     *
     * This constructor is responsible for initializing the class's properties by assigning the provided
     * $f3 object to the $_f3 property and establishing a connection to the database by setting the $_database
     * property to the returned value from the Database::getConnection() method.
     *
     * @param object $f3 The F3 object representing the entire Fat-Free Framework instance.
     *
     * @return void
     */
    public function __construct(object $f3)
    {
        $this->_f3 = $f3;
        $this->_database = Database::getConnection();
    }

    /**
     * Checks if the user is logged in.
     *
     * This method is responsible for checking if the user is logged in by performing the following steps:
     * - Retrieves the 'loggedin' session variable using the F3 object.
     * - If the 'loggedin' session variable is false or not set, it reroutes the user to the '/login' page.
     *
     * @return void
     */
    public function checkLogin(): void
    {
        if (!$this->_f3->get('SESSION.loggedin'))
        {
            $this->_f3->reroute('/login');
        }
    }

    /**
     * Renders the home view.
     *
     * This method is responsible for rendering the home view by performing the following steps:
     * - Checks if the user is logged in.
     * - Clears any session messages.
     * - Instantiates a new Template object.
     * - Renders the 'app/view/home.html' template using the Template object and outputs the result.
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
     * Renders the request access view.
     *
     * This method is responsible for rendering the request access view by performing the following steps:
     * - Clears any session messages.
     * - Instantiates a new Template object.
     * - Renders the 'app/view/request_access.html' template using the Template object and outputs the result.
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
     * Processes an access request.
     *
     * This method validates the given email address and performs the necessary actions
     * depending on the validation results. If the email address is not valid, it sets
     * an error message in the session and renders the request access*/
    public function processAccessRequest(string $email, string $userMessage): void
    {
        if (!Validate::isValidEmail($email))
        {
            $this->_f3->set('SESSION.error', 'Invalid email address');
            $this->renderRequestAccess();
            return;
        }

        if (Validate::isDuplicateEmail($email))
        {
            $this->_f3->set('SESSION.error', 'Email already requested access');
            $this->renderRequestAccess();
            return;
        }

        // In production or dev - change this to the correct domain
        $rootUrl = "https://braedonbillingsley.greenriverdev.com/BASBusinessPortfolio";

        // Send verification email to admin
        $verificationLink = $rootUrl . '/verify-access-request?email=' . urlencode($email);
        $subject = 'Access Request Verification';
        $message = "A new access request has been made. " . "Please review the users message: '" . $userMessage . "'" .
        "\n" . "To verify, click the following link: $verificationLink";
        // Change From: to no-reply@yourdomain.com
        $headers = 'From: no-reply@greenriverdev.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

        // Change TO: to your admin or dev email address
        if (mail('billingsley.braedon@student.greenriver.edu', $subject, $message, $headers))
        {
            $this->_f3->set('SESSION.success', 'Verification email sent to admin');
        } else
        {
            $this->_f3->set('SESSION.error', 'Failed to send verification email');
        }

        $this->renderRequestAccess();
    }

    /**
     * Verifies the access request.
     *
     * This method retrieves the email address from the GET request parameter and
     * performs the following steps:
     * - Checks if the email address is valid. If not, it echoes 'Invalid email address' and returns.
     * - Checks if the email address is already requested access. If so, it echoes 'Email already requested access' and returns.
     * - Creates an access code and sends it to the student's email address.
     * - If an error occurs during the process, it echoes 'Failed to process request'.
     *
     * @return void
     */
    public function verifyAccessRequest(): void
    {
        $email = $this->_f3->get('GET.email');

        if (!Validate::isValidEmail($email))
        {
            echo 'Invalid email address';
            return;
        }

        if (Validate::isDuplicateEmail($email))
        {
            echo 'Email already requested access';
            return;
        }

        try
        {
            Access::createAccessCodeAndMailToStudent($email);
            echo 'Access code sent to ' . htmlspecialchars($email);
        } catch (RuntimeException $e)
        {
            echo 'Failed to process request:' . $e;
        }
    }

    /**
     * Verifies the access code provided.
     *
     * This method takes an access code as a parameter and verifies its validity by
     * calling the `isValidAccessCode` method of the `Validate` class and the `checkAccessCode`
     * method of the `Access` class. If the access code is invalid or doesn't grant access,
     * it sets a session error message and renders the access code view using the `renderAccessCode`
     * method. If the access code is valid and grants access, it redirects either to the registration
     * page or the home page based on the result of the `isRegistrationRequired` method.
     *
     * @param string $accessCode The access code to verify.
     * @return void
     */
    public function verifyAccessCode(string $accessCode): void
    {
        if (!Validate::isValidAccessCode($accessCode) || !Access::checkAccessCodeForEmail($accessCode))
        {
            $this->_f3->set('SESSION.error', 'Invalid access code for the provided email.');
            $this->renderAccessCode();
            return;
        }

        $this->_f3->set('SESSION.access_granted', true);
        $this->_f3->reroute('/login');
    }

    /**
     * Renders the access_code.html view.
     *
     * @return void
     */
    function renderAccessCode(): void
    {
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/access_code.html');
    }

    /**
     * Interprets the error code and returns the corresponding error message.
     *
     * This method takes an error code as input and returns the corresponding error message
     * based on the value of the error code. The error codes and their corresponding error
     * messages are defined in the `Validate` class.
     *
     * @param int $result The error code to interpret.
     * @return string The error message corresponding to the error code.
     */
    function interpretError(int $result): string
    {
        return match ($result)
        {
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
     * Processes the login attempt.
     *
     * This method is responsible for processing the login attempt by performing the following steps:
     * - Sanitizes the username and password inputs using the Validate::sanitizeString method.
     * - Validates the login credentials using the Validate::isValidLogin method.
     * - Retrieves the user's email address from the UserAccount::getEmailByUsername method.
     * - Checks if access code verification is required using the Access::checkAccess method.
     * - Sets the 'SESSION.error' message to 'Access code verification required' and redirects to '/access-code' if access code verification is required.
     * - Authenticates the user using the UserAccount::authenticateUser method and sets session variables if authentication is successful.
     * - Redirects to '/timeline' if authentication is successful.
     * - Sets the 'SESSION.error' message to 'Invalid username or password' and redirects to '/login' if authentication fails.
     * - Sets the 'SESSION.error' message to 'Invalid input' and redirects to '/login' if the login credentials are invalid.
     *
     * @param string $username The username provided by the user.
     * @param string $password The password provided by the user.
     *
     * @return void
     */
    function processLogin(string $username, string $password): void
    {
        $username = Validate::sanitizeString($username);
        $password = Validate::sanitizeString($password);

        if (Validate::isValidLogin($username, $password))
        {
            $userEmail = UserAccount::getEmailByUsername($username);
            if (!Access::checkAccess($userEmail))
            {
                $this->_f3->set('SESSION.error', 'Access code verification required.');
                $this->_f3->reroute('/access-code');
                return;
            }

            $authResult = UserAccount::authenticateUser($username, $password);

            if ($authResult)
            {
                $this->_f3->set('SESSION.loggedin', true);
                $this->_f3->set('SESSION.username', $username);
                $this->_f3->reroute('/timeline');
            } else
            {
                $this->_f3->set('SESSION.error', 'Invalid username or password');
                $this->_f3->reroute('/login');
            }
        } else
        {
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
     * Processes the registration of a new user.
     *
     * This method is responsible for processing the registration of a new user by performing the following steps:
     * - Sanitizes the input values for username, email, password, and confirmPassword using the Validate::sanitizeString() method.
     * - Validates the input values using the Validate::isValidRegistration() method.
     *   - If the input values are invalid, sets the session error message and reroutes to the registration page.
     * - Checks if the user has access granted or if access code verification is required using the SESSION access_granted flag and
     *   the Access::checkAccess() method.
     *   - If access is not granted or access code verification is required, sets the session email and error messages and reroutes
     *     to the access code page.
     * - Hashes the password using the password_hash() function with PASSWORD_DEFAULT algorithm.
     * - Creates a new user using the UserAccount::createUser() method with the username, email, and hashed password.
     *   - If the user is created successfully, clears the SESSION access_granted flag, sets the session success message, and reroutes
     *     to the login page.
     *   - If there is a registration error due to the username or email already being registered, sets the session error message and
     *     reroutes to the registration page.
     *
     * @param string $username The username for the new user.
     * @param string $email The email address for the new user.
     * @param string $password The password for the new user.
     * @param string $confirmPassword The confirmation password for the new user.
     *
     * @return void
     */
    public function processRegister(string $username, string $email, string $password, string $confirmPassword): void
    {
        $username = Validate::sanitizeString($username);
        $email = Validate::sanitizeString($email);
        $password = Validate::sanitizeString($password);
        $confirmPassword = Validate::sanitizeString($confirmPassword);

        if (!Validate::isValidRegistration($username, $email, $password, $confirmPassword))
        {
            $this->_f3->set('SESSION.error', 'Invalid input. Please correct the errors and try again.');
            $this->_f3->reroute('/register');
            return;
        }

        if (!$this->_f3->get('SESSION.access_granted') && !Access::checkAccess($email))
        {
            $this->_f3->set('SESSION.email', $email);
            $this->_f3->set('SESSION.error', 'Access code verification required.');
            $this->_f3->reroute('/access-code');
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $registrationResult = UserAccount::createUser($username, $email, $hashedPassword);

        if ($registrationResult)
        {
            $this->_f3->clear('SESSION.access_granted');
            $this->_f3->set('SESSION.success', 'Registration successful! Please log in.');
            $this->_f3->reroute('/login');
        } else
        {
            $this->_f3->set('SESSION.error', 'Registration error. This username / email is already registered. Please try again.');
            $this->_f3->reroute('/register');
        }
    }

    /**
     * Renders the timeline view.
     *
     * This method is responsible for rendering the timeline view by performing the following steps:
     * - Checks if the user is logged in.
     * - Clears any session messages.
     * - Instantiates a new Template object.
     * - Renders the 'app/view/timeline.html' template using the Template object and outputs the result.
     *
     * @return void
     */
    function renderTimeline(): void
    {
        $this->checkLogin();
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/timeline.html');
    }

    /**
     * Renders the item view.
     *
     * This method is responsible for rendering the item view by performing the following steps:
     * - Checks if the user is logged in.
     * - Clears any session messages.
     * - Instantiates a new Template object.
     * - Renders the 'app/view/item.html' template using the Template object and outputs the result.
     *
     * @return void
     */
    function renderItem(): void
    {
        $this->checkLogin();
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/item.html');
    }

    /**
     * Processes an item.
     *
     * This method is responsible for processing an item by performing the following steps:
     * - Checks if the user is logged in.
     * - Retrieves the item description, title, and type from the $_POST array.
     * - Creates a new Item object using the retrieved data.
     * - Saves the item using the saveItem() method of the Item object.
     * - If an exception occurs during item creation or saving, an error is logged.
     * - Renders the timeline view using the renderTimeline() method.
     *
     * @return void
     */
    public function processItem(): void
    {
        $this->checkLogin();
        $itemDescription = $_POST['itemDescription'];
        $title = $_POST['title'];
        $itemType = $_POST['itemType'];

        try {
            $item = new Item($itemDescription, $itemType, $title);
            $item->saveItem();
        } catch (Exception $e) {
            error_log("Error creating item" . $e);
        }

        $this->renderTimeline();
    }


    /**
     * Creates a timeline object in the Student account.
     *
     * This method is responsible for creating a timeline object in the Student account upon account creation.
     *
     * @return void
     */
    public function createTimeline(): void {
        // TODO Create 1 timeline object in Student account upon account creation.
    }

    /**
     * Renders the task view.
     *
     * This method is responsible for rendering the task view by performing the following steps:
     * - Clears any session messages.
     * - Instantiates a new Template object.
     * - Renders the 'app/view/task.html' template using the Template object and outputs the result.
     *
     * @return void
     */
    function renderTask(): void
    {
        $this->checkLogin();
        $this->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/task.html');
    }

    /**
     * Clears any session messages.
     *
     * This method clears any error or success messages stored in the session.
     *
     * @return void
     */
    public function clearSessionMessages(): void
    {
        $this->_f3->clear('SESSION.error');
        $this->_f3->clear('SESSION.success');
    }

    /**
     * Logout the user.
     *
     * This method clears the session variables using the F3 framework instance variable,
     * destroys the session if it is active, and redirects the user to the login page.
     *
     * @return void
     */
    function logout(): void
    {
        $this->_f3->clear('SESSION');
        if (session_status() === PHP_SESSION_ACTIVE)
        {
            session_destroy();
        }
        $this->_f3->reroute('/login');
    }
}