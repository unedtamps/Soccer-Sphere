<?php
include("./database/query.php");
$res = findAll();
for ($i = 0; $i < count($res); $i++) {
  $res[$i]['paragraf'] = explode('*', $res[$i]['paragraf']);
}
$action = isset($_GET['action']) ? $_GET['action'] : null;
if ($action === "delete") {
  $id = isset($_GET['id']) ? $_GET['id'] : null;
  $img_url = isset($_GET['img_url']) ? $_GET['img_url'] : null;
  $err = DeleteOneArticle(intval($id));
  if ($err != null) {
    echo '<script>alert("Sorry, there was an error deleting aricle:' . $err . '");</script>';
  }
  unlink($img_url);
  header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="public/css/style.css" />
  <script src="public/js/jquery.js"></script>
  <title>Football | Article</title>
</head>

<body>
  <nav class="navbar navbar-expand-sm p-2 mt-0 sticky-top bg-white">
    <div class="container">
      <a class="navbar-brand brand"> SoccerSphere </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse d-sm-flex justify-content-between" id="navbarNavAltMarkup">
        <div class="navbar-nav gap-sm-3 ms-sm-5">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          <a class="nav-link q-category active pointer" aria-current="page" category="indonesia">Indonesia</a>
          <a class="nav-link q-category active pointer" aria-current="page" category="europa">Europa</a>
          <a class="nav-link q-category active pointer" aria-current="page" category="asia">Asia</a>
          <a class="nav-link q-category active pointer" aria-current="page" category="general">General</a>
          <a class="nav-link q-category active pointer" href="./views/post.php" aria-current="page" category="general">Create Article</a>
        </div>
        <div class="navbar-nav gap-sm-5 pb-2 pb-sm-0">
          <button class="btn btn-success active px-4 mb-3 mt-2 mt-sm-0 mb-sm-0" aria-current="page" href="#">
            Login
          </button>
          <button class="btn btn-info active px-4" href="#">Sign Up</button>
        </div>
      </div>
    </div>
  </nav>

  <header>
    <div id="searching-sec">
      <label for="search">Article I Want To Read To Day</label>
      <input type="text" name="search" id="search" placeholder="Find Your Article Here..." class="input-user" />
      <div id="title-bar">
        <h4>Related Results</h4>
        <div class="q-title-bar">
          <?php
          foreach ($res as $row) {
            echo '<li class="query-title pointer" data_id="' . $row['id'] . '">';
            echo '<img src="' . $row['image_url'] . '" alt="" />';
            echo '<p>' . $row['short_title'] . '</p>';
            echo '</li>';
          }
          ?>
        </div>
      </div>
    </div>
  </header>
  <!-- searching tab and preview only -->

  <!-- news preview and selected -->
  <section id="news">
    <div id="container-news">
      <div class="main-news">
        <h1 class="title"></h1>
        <h2 class="sub-title"></h2>
        <p class="tag"></p>
        <h3 class="author"></h3>
        <h4 class="date"></h4>
        <img src="" class="image" alt="" />
        <div class="paragraf"></div>
      </div>
      <div class="side-news"></div>
    </div>
    <div id="container-prev"></div>
  </section>
  <section id="comment">
    <div class="comment-news"></div>
  </section>
  <script>
    $(document).ready(function() {
      // ketika fokus ke input bar ke block
      $("#search").on("focus", function() {
        if (window.innerWidth >= 768) {
          $("#title-bar").css("display", "block");

          $("#title-bar").css("gap", "5rem");
        } else {
          $("#title-bar").css("display", "block");
        }
      });

      $(".query-title").click(function() {
        $("#title-bar").css("display", "none");
      });

      //delay untuk menahan kalau user mau click query titile
      $("#search").on("blur", function() {
        setTimeout(function() {
          $("#title-bar").css("display", "none");
        }, 200);
      });

      $("#search").on("keyup", function() {
        let value = $(this).val().toLowerCase();
        $(".query-title").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });

      var container = $("#container-prev"); // Replace #container with the actual container ID or selector
      var data = <?php echo json_encode($res) ?>

      data.forEach(function(item) {
        let prevNews = $("<div class=prev-news></div>"); // Select the existing element
        prevNews.append(
          $("<h1/>", {
            class: "title query-title pointer",
            text: item.title,
            data_id: item.id,
          })
        );

        prevNews.append(
          $("<p/>", {
            class: "tag q-category pointer",
            text: "#" + item.category,
            category: item.category,
          })
        );

        prevNews.append(
          '<h2 class="sub-title">' + item.sub_title + "</h2>"
        );
        prevNews.append('<h3 class="author">' + item.author + "</h3>");
        prevNews.append('<h4 class="date">' + item.date_time + "</h4>");
        let editCon = $("<div class=edit-container></div>");
        editCon.append(
          $("<a/>", {
            class: "edit-button",
            text: "Edit",
            id: item.id,
            href: `./views/post.php?title_page=Edit%20Article&id=${item.id}`,
          })
        );
        editCon.append(
          $("<a/>", {
            class: "delete-button",
            text: "Delete",
            id: item.id,
            href: `index.php?action=delete&id=${item.id}&img_url=${item.image_url}`
          })
        )
        prevNews.append(editCon)
        prevNews.append(
          '<img src="' +
          item.image_url +
          '" class="image" alt="' +
          item.alt +
          '" />'
        );
        prevNews.append('<div class="separate"></div>');
        container.append(prevNews);
      });

      // display category
      $(".q-category").click(function() {
        container.empty();
        $("#container-news").css("display", "none");
        $("#searching-sec").css("display", "none");
        let category = $(this).attr("category");
        let selData = data.filter((items) => items.category == category);
        selData.forEach(function(item) {
          let prevNews = $("<div class=prev-news></div>"); // Select the existing element
          prevNews.append(
            $("<h1/>", {
              class: "title query-title pointer",
              text: item.title,
              data_id: item.id,
            })
          );
          prevNews.append(
            $("<p/>", {
              class: "tag q-category pointer",
              text: "#" + item.category,
              category: item.category,
            })
          );
          prevNews.append(
            '<h2 class="sub-title">' + item.sub_title + "</h2>"
          );
          prevNews.append('<h3 class="author">' + item.author + "</h3>");
          prevNews.append('<h4 class="date">' + item.date_time + "</h4>");
          prevNews.append(
            '<img src="' +
            item.image_url +
            '" class="image" alt="' +
            item.alt +
            '" />'
          );
          let parfCon = $("<div class='paragraf'></div>");
          item.paragraf.forEach((pr) => {
            parfCon.append("<p>" + pr + "</p>");
          });
          prevNews.append(parfCon);
          prevNews.append('<div class="separate"></div>');
          container.append(prevNews);
          container.css("padding-top", "2rem");
          container.css("display", "block");
        });
        $("html, body").animate({
          scrollTop: 0
        }, "fast");
      });

      $(".query-title").click(function() {
        $("#container-prev").css("display", "none");
        let selId = $(this).attr("data_id");
        let selData = data.find((item) => item.id == selId);
        if (selData) {
          $(".paragraf").empty();
          $(".title").text(selData.title);
          $(".sub-title").text(selData.sub_title);
          $(".tag").text("#" + selData.category);
          $(".author").text(selData.author);
          $(".date").text(selData.date_time);
          $(".image").attr("src", selData.image_url);
          selData.paragraf.forEach((pr) => {
            $(".paragraf").append("<p>" + pr + "</p>");
          });
        }
        $("html, body").animate({
          scrollTop: 0
        }, "fast");
      });
    });
  </script>
</body>

</html>