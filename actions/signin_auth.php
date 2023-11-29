<?php
require_once("../includes/database.php");
require_once("../includes/user.php");

// Retrieve user submitted username and password
$username = $_POST["username"];
$password = $_POST["current-password"];
$allowed_characters = "/^[a-zA-Z0-9 \-]*$/";

// Validate inputs incase JS has been disabled by the user
if (!isset($username) || strlen($username) > 15) {
    // Username not set or longer than 15 characters
    header("Location: /coms501/assignment/signin.php?error=0");
    exit();
} else if (!isset($password) || strlen($password) > 20) {
    // Password not set or longer than 20 characters
    header("Location: /coms501/assignment/signin.php?error=1");
    exit();
} else if (!preg_match($allowed_characters, $username) || !preg_match($allowed_characters, $password)) {
    // Disallowed characters in username or password
    header("Location: /coms501/assignment/signin.php?error=2");
    exit();
}

// Query database for username and password
$db = connect_to_db();
$credentials_query = "SELECT password
                      FROM user
                      WHERE username = ?";
$credentials_stmt = get_prepared_statement($db, $credentials_query, [$username]);
$stored_password = $credentials_stmt->fetch()["password"];
 
// Check if the user password matches the stored password
if (!isset($stored_password)) {
    // Username doesn't exist
    header("Location: /coms501/assignment/signin.php?error=3");
    exit();
} else if ($password !== $stored_password) {
    // Incorrect password
    header("Location: /coms501/assignment/signin.php?error=4");
    exit();
}

// Log the successful sign in
$log_message = date('d/m/Y H:i') . " | $username signed in!\n";
$log_handle = fopen("../assets/logs.txt", 'a');

if ($log_handle) {
    fwrite($log_handle, $log_message);
    fclose($log_handle);
}

// Create the signed in cookie which will expire after a day and is accessible throughout the website
setcookie("user", $username, time() + 86400, "/");

// Send user back to the homepage
header("Location: /coms501/assignment/index.php");
?>