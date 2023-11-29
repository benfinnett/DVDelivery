<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../includes/database.php");

// Connect to database
$db = connect_to_db();

// Build query
$film_id = $_POST["film_id"];
$delete_category_query = "DELETE FROM film_category WHERE film_id = $film_id;";
$delete_actor_query = "DELETE FROM film_actor WHERE film_id = $film_id;";
$delete_rental_query = "DELETE FROM rental WHERE inventory_id IN (SELECT inventory_id FROM inventory WHERE film_id = $film_id);";
$delete_inventory_query = "DELETE FROM inventory WHERE film_id = $film_id;";
$delete_film_query = "DELETE FROM film WHERE film_id = $film_id LIMIT 1;";

// Begin transaction
$db->beginTransaction();
try {
    // Execute queries
    get_statement($db, $delete_category_query);
    get_statement($db, $delete_actor_query);
    get_statement($db, $delete_rental_query);
    get_statement($db, $delete_inventory_query);
    get_statement($db, $delete_film_query);
    $db->commit();
} catch (PDOException $e) {
    $db->rollBack();
    header("Location: /coms501/assignment/films.php?success=false");
    exit();
}

// Send user back to manage page
header("Location: /coms501/assignment/films.php?success=true");
?>