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

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            // Email already exist in the database. Return error code.
            return self::DUPLICATE_EMAIL;
        }
        // Email doesn't exist. Save this new access code in the database associated with the specific email.
        return false;
    }

    //...more validation/sanitization methods as needed
}