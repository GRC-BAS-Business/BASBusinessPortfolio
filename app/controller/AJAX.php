<?php
class AJAX {
    private object $_f3;

    /**
     * Constructs an AJAX object.
     *
     * @param mixed $f3 The global $f3 hive to be assigned to the $_f3 parameter.
     * @return void
     */
    public function __construct(object $f3)
    {
        $this->_f3 = $f3;
    }

    /**
     * Processes the access request and redirects the user.
     *
     * @param string $email The email address entered by the user.
     * @param string $message The message entered by the user.
     * @return void
     */
    function processAccessRequestWithRedirect(string $email, string $message): void
    {
        // Sanitize and validate user inputs here
        $email = Validate::sanitizeString($email);
        $message = Validate::sanitizeString($message);

        if (!Validate::isValidEmail($email)) {
            // Handle invalid email scenario
            $this->_f3->set('SESSION.error', $this->interpretError(1));
            $this->_f3->reroute('request-access');
        } else if (!$message) {
            $this->_f3->set('SESSION.error', $this->interpretError(6));
            $this->_f3->reroute('request-access');
        }

        // Send an email with unique verification link to the admin
        $adminEmail = 'billingsley.braedon@student.greenriver.edu';
        $subject = 'Access Request for BAS Business Portfolio';
        $messageBody = "Email: $email\n\nMessage: $message";
        $headers = "From: $email";
        mail($adminEmail, $subject, $messageBody, $headers);

        // Set success message
        $this->_f3->set('SESSION.success', $this->interpretError(7));
        $this->_f3->reroute('request-access');
    }

    /**
     * Interprets an error code and returns the corresponding error message.
     *
     * @param int $errorCode The error code to be interpreted.
     * @return string The error message associated with the error code. If no corresponding
     *               error message is found, an empty string is returned.
     */
    public function interpretError(int $errorCode): string
    {
        return match ($errorCode) {
            1 => 'Invalid email address',
            6 => 'Message is required',
            7 => 'Access request submitted successfully',
            default => '',
        };
    }
}