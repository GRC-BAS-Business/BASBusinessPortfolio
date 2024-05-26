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
        $this->_f3->clearSessionMessages();
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
            $response['error'] = $this->interpretError(1);
        } else if (!$message) {
            $response['error'] = $this->interpretError(6);
        } else {
            $adminEmail = 'billingsley.braedon@student.greenriver.edu';
            $subject = 'Access Request for BAS Business Portfolio';
            $message = "Email: $email\n\nMessage: $message";
            $headers = "From: $email";
            mail($adminEmail, $subject, $message, $headers);

            $access = new Access();
            $access->createAccessCodeAndMailToStudent($email);

            $response['success'] = $this->interpretError(7);
        }

        echo json_encode($response);
        exit;
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

        // Assume you have a checkAccessCode method in a User class or similar,
        // which compares the provided access code with the one stored in the database.
        if (Access::checkAccessCode($accessCode)) {
            $this->_f3->set('SESSION.isVerified', true);

            // allow the user to sign up
            $this->_f3->reroute('signup');
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
        $this->_f3->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/login.html');
    }

    function processLogin($username, $password): void
    {

    }

    /**
     * Renders the timeline.html view.
     *
     * @return void
     */
    function renderTimeline(): void
    {
        $this->_f3->clearSessionMessages();
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
        $this->_f3->clearSessionMessages();
        $view = new Template();
        echo $view->render('app/view/item.html');
    }


    public function createItemForStudent(int $studentID): void
    {
        // Fetch student id from the session or from the request. Below is just an example
        // $studentId = $_SESSION['userId'];

        $createdDate = new DateTime();
        $itemDescription = $_POST['itemDescription'];
        $title = $_POST['title'];
        $itemType = $_POST['itemType'];

        // you would also need to input your item type and title
        $item = new Item($createdDate, $itemDescription, '', $itemType, $title);

        // Save the item to the database
        $createdItemId = $item->save();
        //TODO create item for student and add item to students portfolio
        //TODO save to the database

        // $timeline->save();
    }

    public function createTimeline(): void {
        // TODO Create 1 timeline object in Student account upon account creation.
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
}