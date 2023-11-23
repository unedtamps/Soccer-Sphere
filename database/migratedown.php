<?php
define('includeDB', true);
include("db.php");
$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';
if ($request_uri === $php_self) {
    if ($_SERVER['PHP_SELF'] !== '/index.php') {
        header('Location: /index.php');
        exit();
    }
}
$down = "DROP TABLE IF EXISTS news";
$conn = conn();
if ($conn->query($down)) {
    error_log("succes to migrate");
} else {
    error_log("Failed to create news table: " . $conn->error);
}