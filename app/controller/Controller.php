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
        $view = new Template();
        echo $view->render('app/view/request_access.html');
    }

    /**
     * Processes an access request and sends verification email to admin.
     *
     * This method is responsible for processing an access request by performing the following steps:
     * - Sanitizes the email and message fields submitted via POST.
     * - Validates the email address.
     * - Checks if the email address is already registered.
     * - If email is valid and not already registered:
     *   - Generates a verification link using the root URL and the encoded email.
     *   - Constructs the email subject and message with the user's message and verification link.
     *   - Sends the verification email using the mail() function with the admin address, subject, message, and headers.
     *   - If email is sent successfully, sets the response status as 'success' and the message as 'Form submitted successfully'.
     *
     * @return void Outputs the JSON-encoded response as the method's output.
     */
    public function processAccessRequest(): void
    {
        $response = array();
        $email = Validate::sanitizeString($_POST['email']);
        $userMessage = Validate::sanitizeString($_POST['message']);

        if (!Validate::isValidEmail($email)) {
            $response['status'] = 'error';
            $response['message'] = 'Please enter a valid email address.';
        } else if (Validate::isDuplicateEmail($email)) {
            $response['status'] = 'error';
            $response['message'] = 'This email is already registered.';
        } else {

            // Send verification email to admin
            $rootUrl = "https://bas-business-portfolio.greenriverdev.com/develop/BASBusinessPortfolio";
            $verificationLink = $rootUrl . '/verify-access-request?email=' . urlencode($email);
            $subject = 'Access Request Verification';
            $message = "A new access request has been made. " . "Please review the user's message: '" . $userMessage . "'" .
                "\n" . "To verify, click the following link: $verificationLink";
            $headers = 'From: no-reply@greenriverdev.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

            if (mail('basbusinessportfolio@gmail.com', $subject, $message, $headers)) {
                $response['status'] = 'success';
                $response['message'] = 'Form submitted successfully.';
            }
        }
        echo json_encode($response);
    }

    /**
     * Verifies the access request.
     *
     * This method is responsible for verifying the access request by performing the following steps:
     * - Sets the response header content type to 'application/json'.
     * - Retrieves the email address from the GET parameters.
     * - Attempts to create an access code and send it via email to the student using the Access class.
     * - If any exception is thrown during the execution, an error message is encoded in JSON format and echoed.
     *
     * @return void
     */
    public function verifyAccessRequest(): void
    {
        header('Content-Type: application/json');
        $email = $this->_f3->get('GET.email');

        try {
            Access::createAccessCodeAndMailToStudent($email);
        } catch (RuntimeException $e) {
            echo json_encode(['error' => 'Failed to process request: ' . $e->getMessage()]);
        }
    }

    /**
     * Processes the access code.
     *
     * This method is responsible for processing the given access code by performing the following steps:
     * - Sets the content type as JSON for the response.
     * - Sanitizes the access code using the "sanitizeString" method from the "Validate" class.
     * - Validates the access code using the "isValidAccessCode" method from the "Validate" class.
     * - If the access code is invalid, sets the response status code as 400 (Bad Request) and outputs an error message.
     * - Checks if the access code exists and is allowed using*/
    public function processAccessCode(string $accessCode): void
    {
        header('Content-Type: application/json');

        // Sanitize the access code
        $accessCode = Validate::sanitizeString($accessCode);

        // Validate the access code
        if (!Validate::isValidAccessCode($accessCode)) {
            http_response_code(HTTP_BAD_REQUEST);
            echo json_encode(['error' => 'Invalid access code format.']);
            return;
        }

        // Check if the access code exists and is allowed
        if (!Access::checkAccessCode($accessCode)) {
            http_response_code(HTTP_BAD_REQUEST);
            echo json_encode(['error' => 'Access code is incorrect']);
            return;
        }

        // If access code is valid and allowed, grant access
        $this->_f3->set('SESSION.access_granted', true);
        $_SESSION['success'] = 'Access granted.';
        http_response_code(HTTP_OK);
        echo json_encode(['success' => 'Access granted. Redirecting...', 'redirect' => 'login']);
    }

    /**
     * Renders the access_code.html view.
     *
     * @return void
     */
    function renderAccessCode(): void
    {
        $view = new Template();
        echo $view->render('app/view/access_code.html');
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
        header('Content-Type: application/json');

        $username = Validate::sanitizeString($username);
        $password = Validate::sanitizeString($password);

        $validationResult = Validate::isValidLogin($username, $password);
        if ($validationResult !== 'valid')
        {
            http_response_code(HTTP_BAD_REQUEST);
            echo json_encode(['error' => $validationResult]);
            return;
        }

        $authResult = UserAccount::authenticateUser($username, $password);

        if ($authResult)
        {
            $this->_f3->set('SESSION.loggedin', true);
            $this->_f3->set('SESSION.username', $username);
            http_response_code(HTTP_OK);
            echo json_encode(['success' => 'Login successful. Redirecting...', 'redirect' => 'timeline']);
        } else
        {
            http_response_code(HTTP_BAD_REQUEST);
            echo json_encode(['error' => 'Invalid username or password']);
        }
    }

    /**
     * Renders the registration page.
     *
     * @return void
     */
    function renderRegister(): void
    {
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
        header('Content-Type: application/json');

        $username = Validate::sanitizeString($username);
        $email = Validate::sanitizeString($email);
        $password = Validate::sanitizeString($password);
        $confirmPassword = Validate::sanitizeString($confirmPassword);

        $validationResult = Validate::isValidRegistration($username, $email, $password, $confirmPassword);

        if (!$validationResult['valid'])
        {
            http_response_code(HTTP_BAD_REQUEST);
            echo json_encode(['error' => $validationResult['errors']]);
            return;
        }

        if (!$this->_f3->get('SESSION.access_granted') && !Access::checkAccess($email))
        {
            $this->_f3->set('SESSION.email', $email);
            http_response_code(HTTP_BAD_REQUEST);
            echo json_encode(['error' => 'Access code verification required.']);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $registrationResult = UserAccount::createUser($username, $email, $hashedPassword);

        if ($registrationResult)
        {
            $this->_f3->clear('SESSION.access_granted');
            http_response_code(HTTP_OK);
            echo json_encode(['success' => 'Registration successful! Please log in.', 'redirect' => 'login']);
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
        $view = new Template();
        echo $view->render('app/view/item.html');
    }

    /**
     * Process item and return a JSON response.
     *
     * This method is responsible for processing the submitted item data and returning a JSON response by performing the following steps:
     * - Sets the Content-Type header to application/json.
     * - Checks if the user is logged in.
     * - Sanitizes and retrieves the item description, title, and type from the $_POST array.
     * - Validates the item data using the Validate::isValidItem() method.
     * - If the validation fails, sets the HTTP status code to HTTP_BAD_REQUEST, encodes the validation errors as JSON, and outputs the result.
     * - If the validation passes, creates a new Item object with the sanitized data, saves the item, sets the HTTP status code to HTTP_OK, encodes a success message and redirect URL as
     * JSON, and outputs the result.
     * - If any exceptions occur during the item creation or saving process, logs the error, sets the HTTP status code to HTTP_INTERNAL_SERVER_ERROR, encodes an error message as JSON, and
     * outputs the result.
     *
     * @return void
     */
    public function processItem(): void
    {
        header('Content-Type: application/json');
        $this->checkLogin();

        $itemDescription = isset($_POST['itemDescription']) ? Validate::sanitizeString($_POST['itemDescription']) : null;
        $title = isset($_POST['title']) ? Validate::sanitizeString($_POST['title']) : null;
        $itemType = isset($_POST['itemType']) ? Validate::sanitizeString($_POST['itemType']) : null;

        $validationResult = Validate::isValidItem($itemDescription, $title, $itemType);

        if (!$validationResult['valid']) {
            http_response_code(HTTP_BAD_REQUEST);
            echo json_encode(['error' => $validationResult['errors']]);
            return;
        }

        try {
            $item = new Item($itemDescription, $itemType, $title);
            $item->saveItem();
            http_response_code(HTTP_OK);
            echo json_encode(['success' => 'Item added successfully.', 'redirect' => 'timeline']);
        } catch (Exception $e) {
            error_log("Error creating item: " . $e);
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            echo json_encode(['error' => 'An error occurred while adding the item. Please try again later.']);
        }
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
        $view = new Template();
        echo $view->render('app/view/task.html');
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
        $this->checkLogin();
        $this->_f3->clear('SESSION');
        if (session_status() === PHP_SESSION_ACTIVE)
        {
            session_destroy();
        }
        $this->_f3->reroute('/login');
    }

    /**
     * Process the GET request for retrieving all items.
     *
     * This method is responsible for processing the GET request to retrieve all items by performing the following steps:
     * - Calls the getItems() method of the Item class to retrieve all items.
     * - Converts the items to an associative array, with each item having the following fields:
     *     - 'creationDate': The creation date of the item in 'Y-m-d H:i:s' format.
     *     - 'itemDescription': The description of the item.
     *     - 'itemType': The type of the item.
     *     - 'title': The title of the item.
     *     - Add more fields as needed.
     * - Sets the 'items' data to the f3 hive.
     * - Outputs the 'items' data encoded in JSON format.
     *
     * @return void
     */
    public function processGetItems(): void
    {
        $items = Item::getItems();

        $itemsArray = array_map(function($item) {
            return [
                'creationDate' => $item->getCreationDate()->format('Y-m-d H:i:s'),
                'itemDescription' => $item->getItemDescription(),
                'itemType' => $item->getItemType(),
                'title' => $item->getTitle(),
            ];
        }, $items);

        $this->_f3->set('items', $itemsArray);
        echo json_encode($this->_f3->get('items'));
    }
}