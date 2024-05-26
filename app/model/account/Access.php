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
    private PDO $_database;

    /**
     * Access constructor.
     *
     * @throws Exception
     */
    public function __construct(){
        $this->_database = Database::getConnection();
    }

    static function checkAccessCode($accessCode)
    {
        try {
            $connection = Database::getConnection();
        } catch (PDOException $e) {
            echo "Database connection error: " . $e->getMessage();
            die();
        }

        // Query for checking the access code can vary based on how you store it in your database.
        $sql = "SELECT * FROM `AccessCodes` WHERE AccessCode = :accessCode";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':accessCode', $accessCode);
        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            return true;
        } else {
            return false;
        }
    }

    private function generateAccessCode(): string
    {
        // I assume that you'll implement this method
        return strtoupper(substr(base_convert(bin2hex(random_bytes(10)), 16, 36), 0, 6));
    }

    /**
     * Generates a random access code and sends it to the student's email address.
     *
     * @param string $email The email address of the student.
     *
     * @return void
     */
    public function createAccessCodeAndMailToStudent(string $email): void
    {
        // Generate a random access code
        $accessCode = $this->generateAccessCode();

        // Save this access code in the database associated with the specific email.
        $this->saveAccessCodeToDatabase($email, $accessCode);

        // The subject of the email
        $subject = 'Your Access Code for BAS Business Portfolio';

        // The body of the email
        $message = "Hello, your access code for BAS Business Portfolio is: $accessCode";

        // Mail headers
        $headers = 'From: billingsley.braedon@student.greenriver.edu' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

        // Send the email to the student
        mail($email, $subject, $message, $headers);
    }

    /**
     * Saves the access code to the database associated with the student's email address.
     *
     * @param string $email The email address of the student.
     * @param string $accessCode The access code generated for the student.
     *
     * @return void
     */
    private function saveAccessCodeToDatabase(string $email, string $accessCode): void
    {
        try {
            // Assuming your $_database is a PDO object and "students" is your table name
            $stmt = $this->_database->prepare("INSERT INTO `AccessCodes` (`Email`, `AccessCode`) VALUES (?, ?)");
            $stmt->execute([$email, $accessCode]);
        } catch (PDOException $e) {
            echo "Error saving access code to the database: " . $e->getMessage();
        }
    }
}
