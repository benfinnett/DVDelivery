<?php
require_once("../includes/database.php");

// Get offset or set to 0 if none was passed
$offset = isset($_GET["offset"]) ? intval($_GET["offset"]) : 0;
$limit = 15;
$film_data = "";

$db = connect_to_db();
$category = $_GET["category"];
$search_query = $_GET["search"];
if (isset($search_query)) {
    // Get 15 films matching the search query and order alphabetically
    $films_query = "SELECT film.film_id, film.title, film.description, film.release_year, film.rental_rate,
                    film.rental_duration, film.special_features, film.rating, language.name,
                    GROUP_CONCAT(tag.name SEPARATOR ' • ') AS tags
                    FROM film
                    INNER JOIN language
                    ON film.language_id = language.language_id
                    LEFT JOIN film_tag
                    ON film.film_id = film_tag.film_id
                    LEFT JOIN tag
                    ON film_tag.tag_id = tag.tag_id
                    WHERE film.title LIKE ?
                    GROUP BY film.film_id
                    ORDER BY film.title ASC
                    LIMIT $offset, $limit";
    $films_stmt = get_prepared_statement($db, $films_query, ["%$search_query%"]);
} else if (isset($category)) {
    // Get 15 films matching the given category and order alphabetically
    $films_query = "SELECT film.film_id, film.title, film.description, film.release_year, film.rental_rate,
                    film.rental_duration, film.special_features, film.rating, language.name,
                    GROUP_CONCAT(tag.name SEPARATOR ' • ') AS tags
                    FROM film
                    INNER JOIN language
                    ON film.language_id = language.language_id
                    LEFT JOIN film_tag
                    ON film.film_id = film_tag.film_id
                    INNER JOIN film_category
                    ON film.film_id = film_category.film_id
                    INNER JOIN category
                    ON film_category.category_id = category.category_id
                    LEFT JOIN tag
                    ON film_tag.tag_id = tag.tag_id
                    WHERE category.name LIKE ?
                    GROUP BY film.film_id
                    ORDER BY film.title ASC
                    LIMIT $offset, $limit";
    $films_stmt = get_prepared_statement($db, $films_query, ["%$category%"]);
} else {
    // Get 15 films ordered alphabetically 
    $films_query = "SELECT film.film_id, film.title, film.description, film.release_year, film.rental_rate,
                    film.rental_duration, film.special_features, film.rating, language.name,
                    GROUP_CONCAT(tag.name SEPARATOR ' • ') AS tags
                    FROM film
                    INNER JOIN language
                    ON film.language_id = language.language_id
                    LEFT JOIN film_tag
                    ON film.film_id = film_tag.film_id
                    LEFT JOIN tag
                    ON film_tag.tag_id = tag.tag_id
                    GROUP BY film.film_id
                    ORDER BY film.title ASC
                    LIMIT $offset, $limit";
    // Query the database and get the results
    $films_stmt = get_statement($db,  $films_query); 
}

// Build the film cards for each film retrieved
while ($row = $films_stmt->fetch(PDO::FETCH_ASSOC)) {
    ob_start(); // Start the output buffer
?>
<div class="film-box">
    <a class="span-link" title="View this film" href="/coms501/assignment/film.php?id=<?php echo $row["film_id"] ?>">
        <span></span>
    </a>
    <div class="film-box-content">
            <h2 class="film-box-title"><?php echo strtolower($row["title"]); ?></h2>
            <h3 class="film-box-year"><?php echo $row["release_year"] . " | " . $row["tags"]; ?></h3>
            <h5 class="film-box-features"><?php echo $row["special_features"]; ?></h3>
            <h4 class="film-box-description"><?php echo $row["description"]; ?></h3>
            <div class="film-box-details">
                <img class="card-lang" src='./assets/images/langs/<?php echo $row["name"]; ?>.png' draggable="false">
                <img class="card-rating" src='./assets/images/ratings/<?php echo $row["rating"]; ?>.png' draggable="false">
            </div>
            <h3 class="film-box-price"><?php echo "£" . $row["rental_rate"]; ?> for 
            <?php
                echo $row["rental_duration"];
                if ($row["rental_duration"] > 1) {
                    echo " days";
                } else {
                    echo " day";
                }
            ?>
            </h3>
        </div>
</div>
<?php
    $film_data .= ob_get_clean(); // Capture the output buffer and append it to $html
}
$response = ["html" => $film_data];
echo json_encode($response);
?>