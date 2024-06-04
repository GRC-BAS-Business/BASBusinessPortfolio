<?php
/**
 *  This is the Validate class for BAS Business Portfolio application
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
class Validate
{
    const REQUEST_SUCCESS = "Access Request was successfully sent";
    const DUPLICATE_EMAIL = "Email already registered with access code.";

    /**
     * Sanitize a string by converting special characters to HTML entities and removing leading/trailing whitespace
     *
     * @param string $data The string to be sanitized
     * @return string The sanitized string
     */
    static function sanitizeString(string $data): string
    {
        return trim($data);
    }

    /**
     * Checks if the given email is valid.
     *
     * @param string $email The email to be validated.
     *
     * @return bool Returns true if the email is valid, false otherwise.
     */
    static function isValidEmail(string $email): bool
    {
        $email = trim($email);
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Checks if the given access code is valid.
     *
     * @param string $accessCode The access code to be validated.
     *
     * @return bool Returns true if the access code is valid, false otherwise.
     */
    static function isValidAccessCode(string $accessCode): bool
    {
        $accessCode = trim($accessCode);
        return ctype_alnum($accessCode);
    }

    static function isDuplicateEmail($email): bool
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM AccessCodes WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC))
        {
            return self::DUPLICATE_EMAIL;
        }
        return false;
    }

    /**
     * Checks if an email is registered in the UserAccount table.
     *
     * @param string $email The email to check if it is registered.
     *
     * @return string Returns the registered email if found, or an empty string if not found.
     */
    public static function isEmailRegistered(string $email): string
    {
        $sql = "SELECT email FROM UserAccount WHERE email = :email";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $isRegistered = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($isRegistered)
        {
            return true;
        } else
        {
            return false;
        }
    }

    /**
     * Checks if the given login credentials are valid.
     *
     * @param string $username The username to be validated.
     * @param string $password The password to be validated.
     *
     * @return string Returns the validation result:
     * - If either the username or password is empty, the function returns 'Username and password are required.'
     * - If the password is less than 8 characters, the function returns 'Password must be at least 8 characters long.'
     * - If the username contains any characters other than alphanumeric, periods, or underscores,
     *   the function returns 'Username can only contain alphanumeric characters, periods, and underscores.'
     * - If the login credentials are valid, the function returns 'valid'.
     */
    public static function isValidLogin(string $username, string $password): string
    {
        if (empty($username) || empty($password))
        {
            return 'Username and password are required.';
        }

        if (strlen($password) < 8)
        {
            return 'Password must be at least 8 characters long.';
        }

        if (!preg_match('/^[a-zA-Z0-9._]+$/', $username))
        {
            return 'Username can only contain alphanumeric characters, periods, and underscores.';
        }

        return 'valid';
    }

    /**
     * Checks if the given registration data is valid.
     *
     * @param string $username The username to be validated.
     * @param string $email The email address to be validated.
     * @param string $password The password to be validated.
     * @param string $confirmPassword The confirmed password to be validated.
     *
     * @return array Returns an array with a 'valid' key that indicates if the registration is valid (true or false),
     *               and an optional 'errors' key that contains any validation errors as an array.
     */
    public static function isValidRegistration(string $username, string $email, string $password, string $confirmPassword): array
    {
        $errors = [];

        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword))
        {
            $errors[] = 'All fields are required.';
        }

        if (!self::isValidEmail($email))
        {
            $errors[] = 'Invalid email address.';
        }

        if (self::isEmailRegistered($email)) {
                $errors[] = 'Email already registered.';
            }

        if ($password !== $confirmPassword)
        {
            $errors[] = 'Passwords do not match.';
        }

        if (strlen($password) < 8)
        {
            $errors[] = 'Password must be at least 8 characters long.';
        }

        if (!preg_match('/^[a-zA-Z0-9._]+$/', $username))
        {
            $errors[] = 'Username can only contain letters, numbers, dots, and underscores.';
        }

        if (empty($errors))
        {
            return ['valid' => true];
        }

        return ['valid' => false, 'errors' => $errors];
    }

    /**
     * Validates the item details.
     *
     * @param string $itemDescription The description of the item.
     * @param string $title The title of the item.
     * @param string $itemType The type of the item.
     *
     * @return array An array containing the validation result and any error messages.
     *               - 'valid' => true if validation is successful, false otherwise.
     *               - 'errors' => An array of error messages if validation fails.
     */
    public static function isValidItem(string $itemDescription, string $title, string $itemType): array
    {
        $errors = [];

        if (empty($itemDescription) || empty($title) || empty($itemType))
        {
            $errors[] = 'All fields are required';
        }

        if (strlen($itemDescription) < 10) {
            $errors[] = 'Item description must be at least 10 characters long';
        }

        if (strlen($title) < 5) {
            $errors[] = 'Title must be at least 5 characters long';
        }

        if (!in_array($itemType, ['Work Experience', 'Resume', 'Certification'])) { // Replace with actual valid types
            $errors[] = 'Invalid item type selected';
        }

        if (empty($errors))
        {
            return ['valid' => true];
        }

        return ['valid' => false, 'errors' => $errors];
    }

    //...more validation/sanitization methods as needed
}