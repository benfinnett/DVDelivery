<?php
// include header and database
require_once("./includes/database.php");

// pretty print the runtime 
function pretty_print_runtime($minutes) {
    $hours = floor($minutes / 60);
    $remaining_minutes = $minutes % 60;
    
    $output = "";
    if ($hours > 0) {
        $output .= $hours . 'h ';
    }
    if ($remaining_minutes > 0) {
        $output .= $remaining_minutes . 'm';
    }
    return $output;
}

$film_id = $_GET["id"];
if (!isset($film_id) || !is_numeric($film_id)) {
    $film_id = 0;
}

// Get film title for page title
$db = connect_to_db();
$page_title_stmt = get_statement($db,  "SELECT title FROM film WHERE film_id = $film_id;");
$film_title = $page_title_stmt->fetch()["title"];

// Redirect to 404 page if film doesn't exist
if (is_null($film_title)) {
    header("Location: /coms501/assignment/404.php");
    exit();
}

// Set page title and include header
$page_title = ucwords(strtolower($film_title)) . " - DVDelivery";
require_once("./includes/header.php");

// Check if user is staff
$username = $_COOKIE["user"];
if (isset($username)) {
    $user = new User($username);
    $user_is_staff = $user->is_staff();
} else {
    $user_is_staff = false;
}

// Build film query
$film_query = "SELECT film.description, film.release_year, film.rental_duration, film.rental_rate,
                film.length, film.replacement_cost, film.rating, film.special_features,
                language.name AS language_name, category.name AS category_name, category.color,
                GROUP_CONCAT(CONCAT(actor.first_name, ' ', actor.last_name) SEPARATOR ', ') AS actors
                FROM film
                INNER JOIN language
                ON film.language_id = language.language_id
                INNER JOIN film_category
                ON film.film_id = film_category.film_id
                INNER JOIN category
                ON film_category.category_id = category.category_id
                LEFT JOIN film_actor
                ON film.film_id = film_actor.film_id
                LEFT JOIN actor
                ON film_actor.actor_id = actor.actor_id
                WHERE film.film_id = $film_id;";
$film_stmt = get_statement($db, $film_query);
$film = $film_stmt->fetch();

// Get tags
$tags_query = "SELECT tag.name
                FROM film
                INNER JOIN film_tag
                ON film.film_id = film_tag.film_id
                INNER JOIN tag
                ON film_tag.tag_id = tag.tag_id
                WHERE film.film_id = $film_id;";
$tags_stmt = get_statement($db, $tags_query);
?>
<main id="film">
    <h1 class="film-title"><?php echo $film_title ?></h1>
    <div class="film-subtitle-details" style="background-color: #<?php echo $film["color"]; ?>; ">
        <h2><?php echo $film["release_year"]; ?></h2>
        <h2><?php echo pretty_print_runtime($film["length"]); ?></h2>
        <h2><?php echo $film["category_name"]; ?></h2>
    </div>

    <div class="film-details">
        <p><?php echo $film["description"];?></p>
        <p id="actors"><b>Actors: </b><?php echo strtolower($film["actors"]); ?></p>
        <p><b>Special Features: </b><?php echo $film["special_features"]; ?></p>
        <div class="film-tags">
            <?php 
            while ($row = $tags_stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<h4>" . $row["name"] . "</h4>";
            }
            ?>
        </div>
        <div class="film-lang-rating">
            <img class="card-lang" src='./assets/images/langs/<?php echo $film["language_name"]; ?>.png' draggable="false">
            <img class="card-rating" src='./assets/images/ratings/<?php echo $film["rating"]; ?>.png' draggable="false">
        </div>
    </div>
    <br>
    <?php
    if (isset($user)) {
        echo '<button id="like-button" type="button" title="Like this film!" onclick="onLikeButtonClick(true);">
                <svg width="50" height="50" viewBox="0 0 50 50">
                    <image xlink:href="./assets/images/thumbs_up.svg" width="50" height="50" />
                </svg>
                <h4>Like this film!</h4>
              </button>';
    } else {
        echo '<button id="like-button" type="button" title="Like this film!" onclick="onLikeButtonClick(false);">
                <svg width="50" height="50" viewBox="0 0 50 50">
                    <image xlink:href="./assets/images/thumbs_up.svg" width="50" height="50" />
                </svg>
                <h4>Like this film!</h4>
              </button>';
    }
    ?>
    <br>
    <div class="film-cost-details">
        <h3>£<?php echo $film["rental_rate"]; ?> per day</h3>
        <h3><?php echo $film["rental_duration"]; ?> day rental</h3>
        <h3>Replacement cost: £<?php echo $film["replacement_cost"]; ?></h3>
        <p>Rent this film from one of our local stores!</p>
    </div>
    
    <?php
    if ($user_is_staff) {
        $confirm_message = "Delete this film?";
        echo "<form action='./actions/delete_film.php' method='POST' onsubmit='return confirm(\"$confirm_message\");'>
                  <input type='hidden' name='film_id' value=$film_id>
                  <button id='delete-film-button' type='submit'>Delete this film</button>
              </form>";
    }
    ?>
</main>
</body>
<script type="text/javascript">
    function onLikeButtonClick(signedIn) {
        // If signed in
        if (signedIn) {
            window.location.replace("/coms501/assignment/like_film.php?film_id=<?php echo $film_id; ?>");
        } else {
            // Send to sign in page 
            window.location.replace("/coms501/assignment/signin.php");
        }
    }
</script>
</html>