<?php
if (!defined('includeDB')) {
    if ($_SERVER['PHP_SELF'] !== '/index.php') {
        header('Location: /index.php');
        exit();
    }
}

function conn()
{
    $env = parse_ini_file(__DIR__ . "/../.env");
    $username = $env["DB_USERNAME"];
    $password = $env["DB_PASSWORD"];
    $host = $env["DB_HOST"];
    $db_name = $env["DB_NAME"];
    $conn = new mysqli($host, $username, $password, $db_name);
    if (!$conn) {
        die("connection failed" . $conn->connect_error);
    }
    return $conn;
}