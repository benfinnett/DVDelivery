<?php
// Absolute path required otherwise PHP files in nested directories accessing database.php will not find config.php 
require_once("/home/usr/public_html/coms501/assignment/includes/config.php");

function connect_to_db() {
    try {
        // connect to db
        $db = new PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME . ";", DB_USER, DB_PASS);
    }
    // handle errors
    catch(Exception $e) {
        echo "There was an error connecting to the database:<br>";
        echo $e->getMessage();
        return null;
    }
    return $db;
}

function get_statement($db, $query) {
    if (!$db instanceof PDO) {
        echo "No PDO object provided!<br>";
        return null;
    } else if ($query === null) {
        echo "No query provided!<br>";
        return null;
    }
    // results of query as statement
    return $db->query($query);
}

function get_prepared_statement($db, $query, $params) {
    if (!$db instanceof PDO) {
        echo "No PDO object provided!<br>";
        return null;
    } else if ($query === null) {
        echo "No query provided!<br>";
        return null;
    } else if ($params === null) {
        echo "No parameters provided!<br>";
        return null;
    }
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

function count_rows($db, $query) {
    $stmt = get_statement($db, $query);
    $count = $stmt->rowCount();
    if ($count === false) {
        $count = 0;
    }
    return $count;
}

function count_rows_prepared($db, $query, $params) {
    $stmt = get_prepared_statement($db, $query, $params);
    $count = $stmt->rowCount();
    if ($count === null) {
        $count = 0;
    }
    return $count;
}
?>
