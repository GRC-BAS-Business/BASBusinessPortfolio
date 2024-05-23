<?php
/**
 *  app/model/Database.php class for BAS Business Portfolio
 *
 *  @authors Noah Lanctot, Mehak Saini, Braedon Billingsley, Will Castillo
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 */
require_once($_SERVER['DOCUMENT_ROOT'] .'/../bas-portfolio-db-config.php');
class Database
{
    private static ?PDO $dbh = null;

    private function __construct() {}

    /**
     *  Get the database connection.
     *  This method establishes a connection to the database using PDO.
     *  @return PDO The PDO object representing the database connection.
     *  @throws PDOException Throws a PDOException if there is an error connecting to the database.
     */
    public static function getConnection(): PDO
    {
        if (is_null(self::$dbh))
        {
            try
            {
                self::$dbh = new PDO(dsn: DB_DSN, username: DB_USERNAME, password: DB_PASSWORD);
            }

            catch (PDOException $e)
            {
                echo "Database connection error: " . $e->getMessage();
                throw $e;
            }
        }
        return self::$dbh;
    }
}
