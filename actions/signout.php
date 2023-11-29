<?php
// Sign a user out of their account
if (isset($_COOKIE['user'])) {
    // Log the successful sign out
    $username = $_COOKIE['user'];
    $log_message = date('d/m/Y H:i') . " | $username signed out!\n";
    $log_handle = fopen("../assets/logs.txt", 'a');

    if ($log_handle) {
        fwrite($log_handle, $log_message);
        fclose($log_handle);
    }

    // Remove the cookie by setting its expire date in the past
    setcookie("user", "", time() - 60, "/");
    unset($_COOKIE['user']);
}
// Send the user back to the homepage
header("Location: /coms501/assignment/index.php");
?>