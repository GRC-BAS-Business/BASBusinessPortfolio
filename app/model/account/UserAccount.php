<?php

/**
 *  This is the UserAccount class
 *
 *  @authors Braedon Billingsley, Will Castillo, Mehak Saini, Noah Lanctot
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
abstract class UserAccount
{
    private int $_userID;
    private string $_username;
    private string $_password;
    private DateTime $_createdDate;
    private bool|int $_isActive;

    /**
     * Constructor for the class.
     *
     * @param int $userID The user ID.
     * @param string $username The username.
     * @param string $password The password.
     * @param DateTime $createdDate The date of creation.
     * @param bool|int $isActive The status of the user.
     */
    public function __construct(int $userID, string $username, string $password, DateTime $createdDate, bool|int $isActive)
    {
        $this->_userID = $userID;
        $this->_username = $username;
        $this->_password = $password;
        $this->_createdDate = $createdDate;
        $this->_isActive = $isActive;
    }

    /**
     * Creates a new user account in the database.
     *
     * @param string $username The username of the new user.
     * @param string $email The email address of the new user.
     * @param string $hashedPassword The hashed password of the new user.
     * @return bool True if the user account was created successfully, false otherwise.
     */
    public static function createUser(string $username, string $email, string $hashedPassword): bool
    {
        try
        {
            $connection = Database::getConnection();
        } catch (PDOException $e)
        {
            error_log("Database connection error: " . $e->getMessage());
            return false;
        }

        $sqlCheck = "SELECT * FROM UserAccount WHERE username = :username OR email = :email";
        $stmtCheck = $connection->prepare($sqlCheck);
        $stmtCheck->bindParam(':username', $username);
        $stmtCheck->bindParam(':email', $email);
        $stmtCheck->execute();

        if($stmtCheck->fetch(PDO::FETCH_ASSOC)){
            return false;
        }

        $createdDate = date('Y-m-d');
        $isActive = 1;

        $sql = "INSERT INTO UserAccount (username, email, password, isActive, CreatedDate) VALUES (:username, :email, :password, :isActive, :createdDate)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':isActive', $isActive, PDO::PARAM_INT);
        $stmt->bindParam(':createdDate', $createdDate);

        return $stmt->execute();
    }

    /**
     * Authenticates a user by checking if the provided username and password are valid.
     *
     * @param string $username The username of the user.
     * @param string $password The password of the user.
     *
     * @return bool True if the user is authenticated, false otherwise.
     */
    public static function authenticateUser(string $username, string $password): bool
    {
        try
        {
            $connection = Database::getConnection();
        } catch (PDOException $e)
        {
            error_log("Database connection error: " . $e->getMessage());
            return false;
        }

        $sql = "SELECT * FROM UserAccount WHERE username = :username AND isActive = 1";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password']))
        {
            return true;
        } else
        {
            return false;
        }
    }

    /**
     * Retrieves the email associated with the given username from the database.
     *
     * @param string $username The username to retrieve the email for.
     * @return string The email associated with the given username.
     */
    public static function getEmailByUsername(string $username): string
    {
        $sql = "SELECT email FROM UserAccount WHERE username = :username";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result)
        {
            return $result['email'];
        } else
        {
            return '';
        }
    }

    /**
     * Retrieves the user ID.
     *
     * @return int The user ID.
     */
    public function getUserID(): int
    {
        return $this->_userID;
    }

    /**
     * Sets the user ID.
     *
     * @param int $userID The user ID to be set.
     * @return void
     */
    public function setUserID(int $userID): void
    {
        $this->_userID = $userID;
    }

    /**
     * Retrieves the username.
     *
     * @return string The username.
     */
    public function getUsername(): string
    {
        return $this->_username;
    }

    /**
     * Sets the username for the user.
     *
     * @param string $username The username to set.
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->_username = $username;
    }

    /**
     * Retrieves the password.
     *
     * @return string The password.
     */
    public function getPassword(): string
    {
        return $this->_password;
    }

    /**
     * Sets the password for the user.
     *
     * @param string $password The new password for the user.
     *
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->_password = $password;
    }

    /**
     * Retrieves the created date.
     *
     * @return DateTime The created date.
     */
    public function getCreatedDate(): DateTime
    {
        return $this->_createdDate;
    }

    /**
     * Sets the created date.
     *
     * @param DateTime $createdDate The created date.
     * @return void
     */
    public function setCreatedDate(DateTime $createdDate): void
    {
        $this->_createdDate = $createdDate;
    }

    /**
     * Retrieves the isActive flag.
     *
     * @return bool|int The isActive flag.
     */
    public function getIsActive(): bool|int
    {
        return $this->_isActive;
    }

    /**
     * Sets the active status of the object.
     *
     * @param bool $isActive The active status to be set.
     *
     * @return void
     */
    public function setIsActive(bool $isActive): void
    {
        $this->_isActive = $isActive;
    }
}