<?php

function getDatabaseConnection()
{
    static $pdo = null;
    if ($pdo == null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';';
        $user = DB_USER;
        $password = DB_PASS;
        try {
            $pdo = new \PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    return $pdo;
}