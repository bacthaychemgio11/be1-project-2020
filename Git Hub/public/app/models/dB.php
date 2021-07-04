<?php
class dB
{
    public static $connection = null;

    //Constructor
    public function __construct()
    {
        if (!self::$connection) {
            self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
            self::$connection->set_charset('utf8mb4');
        }
        return self::$connection;
    }

    //CREATE EXCUTE QUERRIES
    public function select($sql)
    {
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);

        return $result;
    }

    //Close connection
    public function closeConnection()
    {

        self::$connection->close();
        echo "Close connection!";
    }
}
