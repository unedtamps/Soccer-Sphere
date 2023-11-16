<?php
include("db.php");
$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';
if ($request_uri === $php_self) {
    if ($_SERVER['PHP_SELF'] !== '/index.php') {
        header('Location: /index.php');
        exit();
    }
}

function findAll()
{
    $con = conn();
    $query = "SELECT * FROM news";
    $result = mysqli_query($con, $query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}   