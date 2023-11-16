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
$con = conn();
$datas = json_decode(file_get_contents(__DIR__."/../data/news.json"), true);

foreach ($datas as $data) {
    $new_paragraf = mysqli_real_escape_string($con, implode("*", $data["paragraf"]));
    $id = intval($data['id']);
    $shor_title = $data["short-title"];
    $title = $data["title"];
    $category = $data["category"];
    $image_url = $data["image"];
    $sub_title = mysqli_real_escape_string($con, $data["sub_title"]);
    $author = $data["author"];
    $date_time = $data["date"];


    $query = "INSERT INTO news VALUES($id,'$title','$shor_title','$category','$image_url','$sub_title','$author','$date_time','$new_paragraf') ON DUPLICATE KEY UPDATE title='$title',category='$category',short_title='$shor_title',image_url='$image_url',sub_title='$sub_title',author='$author',date_time='$date_time', paragraf='$new_paragraf'";
    if(mysqli_query($con, $query)) {
       error_log("succes to insert") ;
    }else{
        error_log("fail to insert");
    }
}
