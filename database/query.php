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

function findAll()
{
    $con = conn();
    $query = "SELECT * FROM news ORDER BY id DESC";
    $result = mysqli_query($con, $query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_close($con);
    return $data;
}
function findById(int $id)
{
    $con = conn();
    $query = "SELECT * FROM news WHERE id = $id";
    $result = mysqli_query($con, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        mysqli_close($con);
        return $row;
    } else {
        return null;
    }
}

function InsertOneAricle(string $title, string $shortT, string $category, string $imgName, string $subT, string $author, string $date, string $content)
{
    $con = conn();
    $query = "INSERT INTO news (title,short_title,category,image_url,sub_title,author,date_time,paragraf) VALUES('$title','$shortT','$category','$imgName','$subT','$author','$date','$content')";
    if (!mysqli_query($con, $query)) {
        $error = mysqli_error($con);
        mysqli_close($con);
        return $error;
    }
    mysqli_close($con);
    return null;
}
function EditOneAricle(int $id, string $title, string $shortT, string $category, string $subT, string $author, string $content)
{
    $con = conn();
    $query = "UPDATE news SET title = '$title', short_title='$shortT', category='$category', sub_title='$subT', author='$author', paragraf='$content' WHERE id = $id";
    if (!mysqli_query($con, $query)) {
        $error = mysqli_error($con);
        mysqli_close($con);
        return $error;
    }
    mysqli_close($con);
    return null;
}
function DeleteOneArticle(int $id)
{
    $con = conn();
    $query = "DELETE FROM news WHERE id = $id";
    if (!mysqli_query($con, $query)) {
        $error = mysqli_error($con);
        mysqli_close($con);
        return $error;
    }
    mysqli_close($con);
    return null;
}