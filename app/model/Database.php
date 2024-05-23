<?php
/**
 * app/model/Database.php class for BAS Business Portfolio
 *
 * This class handles the database connection using PDO.
 *
 * @package BAS Business Portfolio
 * @authors
 *      Noah Lanctot,
 *      Mehak Saini,
 *      Braedon Billingsley,
 *      Will Castillo
 * @copyright 2024
 * @url https://bas-business-portfolio.greenriverdev.com
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/../bas-portfolio-db-config.php');

class Database
{
    /**
     * @var PDO|null The PDO instance representing the database connection.
     */
    private static ?PDO $dbh = null;

    /**
     * Prevent direct object creation.
     */
    private function __construct() {}

    /**
     * Prevent object cloning.
     */
    private function __clone() {}

    /**
     * Get the database connection.
     *
     * This method establishes a connection to the database using PDO if not already established.
     *
     * @return PDO The PDO object representing the database connection.
     * @throws PDOException If there is an error connecting to the database.
     */
    public static function getConnection(): PDO
    {
        if (self::$dbh === null) {
            try {
                self::$dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
                self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log("Database connection error: " . $e->getMessage());
                throw new PDOException("Database connection error: " . $e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$dbh;
    }
}