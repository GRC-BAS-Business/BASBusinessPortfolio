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
    const INVALID_EMAIL = 1;
    const INVALID_NAME = 2;
    const INCORRECT_PASSWORD = 3;
    const INCORRECT_USERNAME = 4;
    const INCORRECT_ACCESS_CODE = 5;
    const INVALID_STRING = 6;
    const REQUEST_SUCCESS = 7;
    const DUPLICATE_EMAIL = 8;

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
     * Validates login credentials.
     *
     * @param string $username The username provided by the user.
     * @param string $password The password provided by the user.
     * @return bool True if the input is valid, otherwise false.
     */
    public static function isValidLogin(string $username, string $password): bool
    {
        if (empty($username) || empty($password))
        {
            return false;
        }

        if (strlen($password) < 8)
        {
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9._]+$/', $username))
        {
            return false;
        }

        return true;
    }

    /**
     * Checks if the given registration details are valid.
     *
     * @param string $username The username to be validated.
     * @param string $email The email address to be validated.
     * @param string $password The password to be validated.
     * @param string $confirmPassword The confirmed password to be validated.
     *
     * @return bool Returns true if the registration details are valid, false otherwise.
     */
    public static function isValidRegistration(string $username, string $email, string $password, string $confirmPassword): bool
    {
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword))
        {
            return false;
        }

        if (!self::isValidEmail($email))
        {
            return false;
        }

        if ($password !== $confirmPassword)
        {
            return false;
        }

        if (strlen($password) < 8)
        {
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9._]+$/', $username))
        {
            return false;
        }

        return true;
    }

    //...more validation/sanitization methods as needed
}