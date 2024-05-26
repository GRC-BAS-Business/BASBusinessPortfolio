<?php

/**
 *  This is the Student class
 *
 *  @authors Braedon Billingsley, Will Castillo, Mehak Saini, Noah Lanctot
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
class Access
{
    const DUPLICATE_EMAIL = 8;

    public static function checkAccessCode($accessCode): bool
    {
        try {
            // Query for checking the access code can vary based on how you store it in your database.
            $sql = "SELECT * FROM `AccessCodes` WHERE `AccessCode` = :accessCode";
            $stmt = Database::getConnection()->prepare($sql);

            if ($stmt === false) {
                var_dump(Database::getConnection()->errorInfo());
                return false;
            }

            $stmt->bindParam(':accessCode', $accessCode);
            $stmt->execute();

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            echo 'PDOException: ' . $e->getMessage();
            return false;
        }

        return false;
    }

    public static function generateAccessCode(): string
    {
        return bin2hex(random_bytes(4));
    }

    /**
     * Generates a random access code and sends it via email to a student.
     *
     * @param string $email The email address of the student.
     * @return int Returns one of the following:
     *   - Validate::REQUEST_SUCCESS if the access code was created and sent successfully.
     *   - self::DUPLICATE_EMAIL if the email already exists in the database.
     */
    public static function createAccessCodeAndMailToStudent(string $email): int
    {
        // Generate a random access code
        $accessCode = self::generateAccessCode();

        // Check if the email already exists in the database
        // Check if the email already exists in the database
        $sql = "SELECT * FROM `AccessCodes` WHERE `Email` = :email";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if (Validate::isDuplicateEmail($email)) {
            return Validate::DUPLICATE_EMAIL;
        } else {
        // Email doesn't exist. Save this new access code in the database associated with the specific email.
        try {
            self::saveAccessCodeToDatabase($email, $accessCode);
        } catch (RuntimeException $e) {
            error_log($e->getMessage());
            throw new RuntimeException('Failed to save access code to database.');
        }
    }

        // The subject of the email
        $subject = 'Your Access Code for BAS Business Portfolio';

        // The body of the email
        $message = "Hello, your access code for BAS Business Portfolio is: $accessCode";

        // Mail headers
        $headers = 'From: billingsley.braedon@student.greenriver.edu' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

        // Send the email to the student
        mail($email, $subject, $message, $headers);
        return Validate::REQUEST_SUCCESS;
    }

    /**
     * Saves the access code to the database associated with the student's email address.
     *
     * @param string $email The email address of the student.
     * @param string $accessCode The access code generated for the student.
     *
     * @return void
     */
    public static function saveAccessCodeToDatabase(string $email, string $accessCode): void
    {
        $sql = "INSERT INTO `AccessCodes` (`Email`, `AccessCode`) VALUES (:email, :accessCode)";
        $stmt = Database::getConnection()->prepare($sql);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':accessCode', $accessCode);

        $isInserted = $stmt->execute();

        // Check if the execution was successful
        if ($isInserted){
            echo "Access code has been saved successfully.";
        }else{
            echo "Database error: ";  // It is generally the error message
        }
    }
}
