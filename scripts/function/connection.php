<?php
class DatabaseConnection
{
    private $con;

    function __construct()
    {
        $server = 'localhost:3306';
        $user = 'root';
        $pwd = '';
        $schema = 'mealmasterV2';

        try {
            $this->con = new PDO('mysql:host=' . $server . ';dbname=' . $schema . ';charset=utf8', $user, $pwd);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo 'Fehler bei der Verbindung zur Datenbank: ' . $e->getMessage();
        }
    }
}
