<?php

use Random\RandomException;

/**
 *  This is the Student class
 *
 *  @authors Braedon Billingsley, Will Castillo, Mehak Saini, Noah Lanctot
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
class Student extends UserAccount {
    private string $_email;
    private string $_firstName;
    private string $_lastName;
    private bool|int $_hasGraduated;
    private int $_portfolioTimelineID;
    private int $_userID;

    /**
     * Constructor method for creating a new instance of the class.
     *
     * @param string $username The username for the user.
     * @param string $password The password for the user.
     * @param string $email The email address for the user.
     * @param string $firstName The first name of the user.
     * @param string $lastName The last name of the user.
     * @param bool|int $hasGraduated Flag indicating if the user has graduated or not.
     * @param int $portfolioTimelineID The ID of the portfolio timeline.
     * @param int $userID The ID of the user.
     *
     * @return void
     */
    public function __construct(string $username, string $password, string $email, string $firstName, string $lastName, bool|int $hasGraduated, int $portfolioTimelineID, int $userID)
    {
        parent::__construct($userID, $username, $password, new DateTime(), false);
        $this->_email = $email;
        $this->_firstName = $firstName;
        $this->_lastName = $lastName;
        $this->_hasGraduated = $hasGraduated;
        $this->_portfolioTimelineID = $portfolioTimelineID;
        $this->_userID = $userID;
    }

    static function validateLogin($username, $password): bool
    {
        if (empty($username) || empty($password)) {
            return false;
        }

        if (strlen($password) < 8) {
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9._]+$/', $username)) {
            return false;
        }

        return true;
    }

    static function authenticateUser($username, $password)
    {
        try {
            $connection = DatabaseConnection::getConnection();
        } catch (PDOException $e) {
            echo "DatabaseConnection connection error: " . $e->getMessage();
            die();
        }

        $sql = "SELECT * FROM UserAccount WHERE Username = :username";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password == $user['Password']) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Retrieves the email associated with this object.
     *
     * @return string The email address.
     */
    public function getEmail(): string
    {
        return $this->_email;
    }

    /**
     * Sets the email address for this object.
     *
     * @param string $email The email address to be set.
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->_email = $email;
    }

    /**
     * Retrieves the first name associated with this object.
     *
     * @return string The first name of the user.
     */
    public function getFirstName(): string
    {
        return $this->_firstName;
    }

    /**
     * Set the first name for this object.
     *
     * @param string $firstName The first name to be set.
     * @return void
     */
    public function setFirstName(string $firstName): void
    {
        $this->_firstName = $firstName;
    }

    /**
     * Retrieves the last name associated with this object.
     *
     * @return string The last name of the user.
     */
    public function getLastName(): string
    {
        return $this->_lastName;
    }

    /**
     * Sets the last name for this object.
     *
     * @param string $lastName The last name of the object.
     * @return void
     */
    public function setLastName(string $lastName): void
    {
        $this->_lastName = $lastName;
    }

    /**
     * Retrieves the graduation status of the object.
     *
     * @return bool|int The graduation status of the object. Returns true if the object has graduated,
     * false if the object has not graduated, and null if the graduation status is unknown.
     */
    public function getHasGraduated(): bool|int
    {
        return $this->_hasGraduated;
    }

    /**
     * Sets the value indicating whether the user has graduated.
     *
     * @param bool|int $hasGraduated The value indicating whether the user has graduated.
     *                              This can be a boolean value or an integer (0 for false, non-zero for true).
     * @return void
     */
    public function setHasGraduated(bool|int $hasGraduated): void
    {
        $this->_hasGraduated = $hasGraduated;
    }

    /**
     * Retrieves the portfolio timeline ID associated with this object.
     *
     * @return int The ID of the portfolio timeline.
     */
    public function getPortfolioTimelineID(): int
    {
        return $this->_portfolioTimelineID;
    }

    /**
     * Sets the portfolio timeline ID for this object.
     *
     * @param int $portfolioTimelineID The ID of the portfolio timeline.
     *
     * @return void
     */
    public function setPortfolioTimelineID(int $portfolioTimelineID): void
    {
        $this->_portfolioTimelineID = $portfolioTimelineID;
    }

    /**
     * Retrieves the user ID associated with this object.
     *
     * @return int The ID of the user.
     */
    public function getUserID(): int
    {
        return $this->_userID;
    }

    /**
     * Set the user ID.
     *
     * @param int $userID The ID of the user.
     *
     * @return void
     */
    public function setUserID(int $userID): void
    {
        $this->_userID = $userID;
    }
}
