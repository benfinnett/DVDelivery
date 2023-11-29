<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../includes/database.php");

// Connect to database and INSERT new category
$db = connect_to_db();

// Extract properties
$name = $_POST["film-name"];
$description = $_POST["film-description"];
$category = $_POST["film-category"];
$year = $_POST["film-year"];
$lang = $_POST["film-lang"];
$rental_duration = $_POST["film-rental-duration"];
$rental_rate = $_POST["film-rental-rate"];
$duration = $_POST["film-duration"];
$replacement_cost = $_POST["film-replacement"];
$rating = $_POST["film-rating"];

// If set, format film features
if (isset($_POST["film-features"])) {
    $features = implode(",", $_POST["film-features"]);
} else {
    $features = NULL;
}

// Get language ID
$lang_query = "SELECT language_id FROM `language` WHERE `name` = '$lang';";
$lang_id = intval(get_statement($db, $lang_query)->fetch()["language_id"]);

// Get category ID
$category_id_query = "SELECT category_id FROM category WHERE name = '$category';";
$category_id = intval(get_statement($db, $category_id_query)->fetch()["category_id"]);

// Create film insert query
$film_query = "INSERT INTO film 
               (`film_id`, `title`, `description`, `release_year`, `language_id`, 
               `original_language_id`, `rental_duration`, `rental_rate`, `length`, 
               `replacement_cost`, `rating`, `special_features`, `last_update`) 
               VALUES 
               (NULL, ?, ?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, current_timestamp());";
$film_params = [$name, $description, $year, $lang_id, $rental_duration, $rental_rate,
           $duration, $replacement_cost, $rating, $features];

// Create film_category insert query
$category_query = "INSERT INTO film_category
                   (film_id, category_id, last_update)
                   VALUES
                   (?, ?, current_timestamp());";

// Begin transaction
$db->beginTransaction();
try {
    // Insert new film
    $film_stmt = $db->prepare($film_query);
    $film_stmt->execute($film_params);

    // Get new film's ID
    $latest_film_query = "SELECT film_id
                            FROM film
                            ORDER BY film_id DESC
                            LIMIT 1;";
    $new_film_id = intval(get_statement($db, $latest_film_query)->fetch()["film_id"]);
    $category_params = [$new_film_id, $category_id];

    // Insert new film_category
    $category_stmt = $db->prepare($category_query);
    $category_stmt->execute($category_params);

    // Commit queries to database
    $db->commit();
} catch (PDOException $e) {
    // If the SQL query fails, rollback to a safe state
    $db->rollBack();
    header("Location: /coms501/assignment/manage.php?success=false");
    exit();
}

// Send user back to manage page
header("Location: /coms501/assignment/manage.php?success=film");
?>