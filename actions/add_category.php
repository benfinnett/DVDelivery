<?php
require_once("../includes/database.php");

// Connect to database and INSERT new category
$db = connect_to_db();

// Prepare query
$insert_query = "INSERT INTO category
                 (category_id, name, color, last_update)
                 VALUES
                 (NULL, ?, ?, current_timestamp())";
$params = [$_POST["category-name"], substr($_POST["category-color"], 1)];
try {
    get_prepared_statement($db, $insert_query, $params);
} catch (PDOException $e) {
    header("Location: /coms501/assignment/manage.php?success=false");
    exit();
}

// Send user back to manage page
header("Location: /coms501/assignment/manage.php?success=category");
?>