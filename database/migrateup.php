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

$create_table_query = "CREATE TABLE
    IF NOT EXISTS news (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(128) NOT NULL,
        short_title VARCHAR(128) NOT NULL,
        category ENUM('indonesia', 'asia', 'europa', 'general') NOT NULL,
        image_url VARCHAR(256) NOT NULL,
        sub_title VARCHAR(512) NOT NULL,
        author VARCHAR(64) NOT NULL,
        date_time VARCHAR(32) NOT NULL,
        paragraf TEXT NOT NULL
    )";
$conn = conn();
if ($conn->query($create_table_query)) {
    error_log("succes to migrate");
} else {
    error_log("Failed to create news table: " . $conn->error);
}
