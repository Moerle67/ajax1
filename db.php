<?php
class DB {
    private const HOST = 'localhost:3306';
    private const DBNAME = 'udemyajax1';
    private const USER = 'root';
    private const PASSWORD = "";

    private static $DBConnection;

    public static function connectDB() {
        if (self::$DBConnection === null) {
            self::$DBConnection = new PDO('mysql:host='.self::HOST.';dbname='.self::DBNAME.';charset=utf8', self::USER, self::PASSWORD);

            // Error Mode use Exceptions, to catch then ans output
            self::$DBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Not every DBMS can't handle Prepared Statements. Emulate can helps here, but for this project it's turned off.
            self::$DBConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return self::$DBConnection;
    }
    
}
?>