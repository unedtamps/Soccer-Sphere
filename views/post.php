<?php
define('includeQuery', true);
include('../database/query.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'Edit Article') {
    $title = $_POST['title'];
    $shortT = $_POST['short-title'];
    $subT = $_POST['sub-title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $content =  $_POST['content'];
    $id = intval($_POST['id']);

    $err = EditOneAricle($id, $title, $shortT, $category, $subT, $author, $content);
    if ($err != null) {
        echo '<script>alert("Sorry, there was an error creating aricle:' . $err . '");history.go(-1);</script>';
    }
    if ($_SERVER['PHP_SELF'] !== '/index.php') {
        header('Location: /index.php');
        exit();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'Create Article') {
    $title = $_POST['title'];
    $shortT = $_POST['short-title'];
    $subT = $_POST['sub-title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $content =  $_POST['content'];
    // echo $title.$shortT.$subT.$author.$category.$pic.$content;
    $allowExtension = ['png', 'jpg', 'jpeg'];
    $fileExtension = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowExtension)) {
        echo '<script>alert("Sorry, there was an error uploading your file. you extension only png jpg and jpeg.");history.go(-1);</script>';
        return;
    }

    $fileSize = $_FILES["picture"]["size"] / 1024; // convert to kilobytes
    if ($fileSize > 200) {
        echo '<script>alert("File size exceeds the maximum limit of 200KB.");history.go(-1);</script>';
        return;
    }

    function generateRandomString($length = 10)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    $imgTarget = "../public/img/";
    $randomSTR = generateRandomString(20);
    $targetFileName = $imgTarget . $randomSTR . "." . $fileExtension;
    $date = date('d F, Y');
    $imgName = "./public/img/" . $randomSTR . "." . $fileExtension;


    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFileName)) {
        $err = InsertOneAricle($title, $shortT, $category, $imgName, $subT, $author, $date, $content);
        if ($err != null) {
            echo '<script>alert("Sorry, there was an error creating aricle:' . $err . '");history.go(-1);</script>';
        }
        if ($_SERVER['PHP_SELF'] !== '/index.php') {
            header('Location: /index.php');
            exit();
        }
    } else {
        echo '<script>alert("Sorry, there was an error uploading your file.");history.go(-1);</script>';
    }
} else {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    if ($id != null) {
        $res = findById(intval($id));
    }
    $title = isset($_GET['title_page']) ? $_GET['title_page'] : null;
    if ($title == null) {
        $title = "Create Article";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="../public/js/jquery.js"></script>
    <link rel="stylesheet" href="/public/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <script src="public/js/jquery.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Football | Article</title>
</head>

<body>
    <nav class="navbar navbar-expand-sm p-2 mt-0 sticky-top bg-white">
        <div class="container">
            <a class="navbar-brand brand" href="/index.php"> SoccerSphere </a>
        </div>
    </nav>
    <section class="mt-8 md:w-[60%] w-[70%] mx-auto mb-4 ">
        <h1 class="text-5xl font-extrabold font-sans text-center"><?php echo $title ?></h1>
        <form action="" method="post" class="flex flex-col items-center  mt-8" enctype="multipart/form-data">
            <input class="hidden" id="id" name="id" value="<?php echo $id; ?>">
            <input class="hidden" id="action" name="action" value="<?php echo $title; ?>">
            <div class="mb-2">
                <label for="title" class="block mb-0 font-bold md:text-lg text-sm text-slate-950">Title</label>
                <input type="text" id="title" name="title" class="bg-slate-400  text-white border border-gray-900  text-sm md:text-[1rem]  rounded-lg focus:ring-blue-500 focus:border-blue-500 block md:w-[30rem] w-80 h-auto  px-2 py-2" placeholder="Write you article title" required value="<?php echo isset($res['title']) ? htmlspecialchars($res['title'], ENT_QUOTES) : ''; ?>">
            </div>
            <div class="mb-2">
                <label for="short-title" class="block mb-0  font-bold md:text-lg text-sm text-slate-950">Keyword</label>
                <input type="text" id="short-input" name="short-title" class="bg-slate-400  text-white border border-gray-900  text-sm md:text-[1rem] rounded-lg focus:ring-blue-500 focus:border-blue-500 block md:w-[30rem] w-80  px-2 py-2" placeholder="Write you title in short" required value="<?php echo isset($res['short_title']) ? htmlspecialchars($res['short_title'], ENT_QUOTES) : ''; ?>">
                <label for="sub-title" class="block mt-2  font-bold text-sm md:text-lg text-slate-950">Sub Title</label>
                <input type="text" id="sub-title" name="sub-title" class="bg-slate-400  text-white border border-gray-900  text-sm md:text-[1rem] rounded-lg focus:ring-blue-500 focus:border-blue-500 block md:w-[30rem] w-80  px-2 py-2" placeholder="Write something after your title" required value="<?php echo isset($res['sub_title']) ? htmlspecialchars($res['sub_title'], ENT_QUOTES) : ''; ?>">
            </div>
            <div class="mb-2">
                <label for="author" class="block mb-0  font-bold md:text-lg text-sm text-slate-950">Author</label>
                <input type="text" id="author" name="author" class="bg-slate-400  text-white border border-gray-900  text-sm md:text-[1rem] rounded-lg focus:ring-blue-500 focus:border-blue-500 block md:w-[30rem] w-80  px-2 py-2" placeholder="" required value="<?php echo isset($res['author']) ? htmlspecialchars($res['author'], ENT_QUOTES) : ''; ?>">
            </div>
            <div class="mb-2">
                <label for="category" class="block mb-0  font-bold md:text-lg text-sm text-slate-950">Chose Category</label>
                <select id="category" name="category" class="bg-slate-400  text-white border border-gray-900  text-sm md:text-[1rem] rounded-lg focus:ring-blue-500 focus:border-blue-500 block md:w-[30rem] w-80  px-2 py-2" required>
                    <option disabled selected value="" class="font-bold">-- Choose category --</option>
                    <option value="asia" <?php echo (isset($res['category']) && $res['category'] === "asia") ? 'selected' : ''; ?>>Asia</option>
                    <option value="europa" <?php echo (isset($res['category']) && $res['category'] === "europa") ? 'selected' : ''; ?>>Europa</option>
                    <option value="indonesia" <?php echo (isset($res['category']) && $res['category'] === "indonesia") ? 'selected' : ''; ?>>Indonesia</option>
                    <option value="general" <?php echo (isset($res['category']) && $res['category'] === "general") ? 'selected' : ''; ?>>General</option>
                </select>
                <?php if ($title == "Create Article") { ?>
                    <label for="picture" class="block mb-0  font-bold md:text-lg text-sm text-slate-950">Picture</label>
                    <input type="file" id="picture" name="picture" class="bg-slate-400  text-white cursor-pointer border border-gray-900  text-sm md:text-[1rem] rounded-lg focus:ring-blue-500 focus:border-blue-500 block md:w-[30rem] w-80 px-2 py-2" placeholder="" required>
                <?php } ?>
            </div>
            <div class="mb-3">
                <label for="content" class="block mb-2  font-bold md:text-lg text-sm text-slate-950">Content</label>
                <textarea type="text" id="content" name="content" rows="7" cols="50" class="bg-slate-400  text-white border border-gray-900  text-sm md:text-[1rem] rounded-lg focus:ring-blue-500 focus:border-blue-500 block md:w-[30rem] w-80  px-2 py-2" placeholder="" required><?php echo isset($res['paragraf']) ? htmlspecialchars($res['paragraf'], ENT_QUOTES):' '; ?></textarea>
            </div>
            <button class="bg-cyan-800 rounded-lg shadow text-white px-4 py-2" type="submit">Submit</button>
        </form>
    </section>
    <script>
        $(document).ready(function() {
            // Get the textarea element
            var textarea = document.getElementById('content');

            // Set the cursor position to the end when the textarea is clicked
            textarea.addEventListener('click', function() {
                this.setSelectionRange(this.value.length, this.value.length);
            });
        });
    </script>
</body>

</html>