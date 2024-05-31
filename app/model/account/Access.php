<?php
/**
 *  This is the Access class
 *
 *  @authors Braedon Billingsley, Will Castillo, Mehak Saini, Noah Lanctot
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
class Access
{
    /**
     * Check if the given access code exists in the AccessCodes table.
     *
     * @param string $accessCode The access code to check.
     *
     * @return bool Returns true if the access code exists, false otherwise.
     */
    public static function checkAccessCodeForEmail(string $accessCode): bool
    {
        $sql = "SELECT * FROM `AccessCodes` WHERE `AccessCode` = :accessCode";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindParam(':accessCode', $accessCode);
        $stmt->execute();
        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Checks if a student has access based on their email address.
     *
     * @param string $email The email address of the student
     *
     * @return bool Returns true if the student has access, false otherwise.
     */
    public static function checkAccess(string $email): bool
    {
        $sql = "SELECT * FROM `AccessCodes` WHERE `Email` = :email";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false;
    }


    /**
     * Generates a random access code.
     *
     * This method generates a random access code by calling the `random_bytes` function
     * to get 4 random bytes and then converts the bytes to hexadecimal using the `bin2hex` function.
     *
     * @return string The generated access code.
     */
    public static function generateAccessCode(): string
    {
        try
        {
            return bin2hex(random_bytes(4));
        } catch (Exception $e) {
            return $e->getMessage() . 'Random Exception';
        }
    }

    /**
     * Generates an access code, saves it to the database, and mails it to the student.
     *
     * @param string $email The email address of the student
     *
     * @return int Returns a constant representing the result of the operation:
     *             - Validate::DUPLICATE_EMAIL if the email address is already in the database
     *             - Validate::REQUEST_SUCCESS if the access code is created and mailed successfully
     */
    public static function createAccessCodeAndMailToStudent(string $email): int
    {
        if (Validate::isDuplicateEmail($email))
        {
            return Validate::DUPLICATE_EMAIL;
        }

        $accessCode = self::generateAccessCode();
        self::saveAccessCodeToDatabase($email, $accessCode);

        $subject = 'Your Access Code for BAS Business Portfolio';
        $message = "Hello, your access code for BAS Business Portfolio is: $accessCode";
        $headers = 'From: no-reply@greenriverdev.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $message, $headers);
        return Validate::REQUEST_SUCCESS;
    }

    /**
     * Saves an access code to the database.
     *
     * @param string $email The email address of the student.
     * @param string $accessCode The access code to save.
     *
     * @return void
     */
    public static function saveAccessCodeToDatabase(string $email, string $accessCode): void
    {
        $sql = "INSERT INTO `AccessCodes` (`Email`, `AccessCode`) VALUES (:email, :accessCode)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':accessCode', $accessCode);
        $stmt->execute();
    }
}
