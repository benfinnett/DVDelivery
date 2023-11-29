<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="icon" type="image/x-icon" href="./assets/images/favicon.ico">
    <link rel="stylesheet" href="style.css">
</head>
<script type="text/javascript">
    // JS for profile modal
    function open_profile() {
        document.querySelector(".modal").classList.remove("hidden");
        document.querySelector(".overlay").classList.remove("hidden");
        document.querySelector(".nav-list").classList.remove("active");
        document.querySelector(".hamburger").classList.remove("active");
    }

    function close_profile() {
        document.querySelector(".modal").classList.add("hidden");
        document.querySelector(".overlay").classList.add("hidden");
    }
</script>
<script type="text/javascript">
    // JS for hamburger modal
    function hamburgerMenu() {
        document.querySelector(".nav-list").classList.toggle("active");
        document.querySelector(".hamburger").classList.toggle("active");
    }
</script>
<?php
// Define page variables
require_once("user.php");

$current_page = $_SERVER["PHP_SELF"];
$index_page = "/coms501/assignment/index.php";
$pages = [
    "Recommended" => "/coms501/assignment/recommended.php",
    "Categories" => "/coms501/assignment/categories.php",
    "Films" => "/coms501/assignment/films.php"
];

// Add manage page link if staff user is signed in
$username = $_COOKIE["user"];
if (isset($username)) {
    $user = new User($username);
    if ($user->is_staff()) {
        $pages["Manage"] = "/coms501/assignment/manage.php";
    }
}
?>

<body>
    <!-- Navigation Menu -->
    <header>
        <nav>
            <div class="logo">
                <a href="./index.php">
                    <img src="./assets/images/logo.png" alt="DVDelivery Logo" id="img-logo" draggable="false">
                </a>
                <h1><a href="./index.php" id="header-title">DVDelivery</a></h1>
            </div>
            <ul class="nav-list">
                <?php                
                foreach ($pages as $label => $page) {
                    $attributes = ($current_page === $page) ? 'aria-current="page"' : '';
                    echo '<li><a href="' . $page . '" ' . $attributes . '>' . $label . '</a></li>';
                }
                if (isset($user)) {
                    echo "<button id='header-profile-button' class='orange-button' " .
                    "type='button' title='Profile' " .
                    "onclick='open_profile();'>Profile</button>";
                } else {
                    echo "<button id='header-profile-button' class='orange-button' " .
                    "type='button' title='Sign In' " .
                    "onclick=\"location.href='/coms501/assignment/signin.php'\">Sign In</button>";
                }
                ?>
            </ul>
            <button id="hamburger-button" class="hamburger" type="button" title="Menu" onclick="hamburgerMenu();">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </nav>
    </header>
    <!-- Profile Modal -->
    <section class="modal hidden">
        <div id="profile-modal-title">
            <h2><?php echo $username ?>'s Profile</h2>
            <button class="close-button" type="button" title="Close" onclick="close_profile();">X</button>
        </div>
        <a href="/coms501/assignment/actions/signout.php">
            <div class="signout">
                <h4>Sign Out</h4>
            </div>
        </a>
    </section>
    <div class="overlay hidden"></div>