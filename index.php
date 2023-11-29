<?php
    // include header and database
    $page_title = "DVDelivery";
    require("./includes/header.php");
    require_once("./includes/database.php");

    // Choose three random films from the latest year and get their details
    $recent_films_query = "SELECT film.film_id, film.title, film.description, film.rental_rate, language.name, film.rating
                           FROM film
                           INNER JOIN language
                           ON film.language_id = language.language_id
                           WHERE film.release_year = (SELECT MAX(film.release_year) FROM film)
                           ORDER BY RAND()
                           LIMIT 3;";

    // Select the three categories with the most films 
    $hot_categories_query = "SELECT category.name, category.color, COUNT(film_category.category_id) AS `occurance`
                             FROM film_category
                             INNER JOIN category
                             ON film_category.category_id = category.category_id
                             GROUP BY film_category.category_id
                             ORDER BY `occurance` DESC
                             LIMIT 4;";

    // Query the database and get the results
    $db = connect_to_db();
    $recent_films_stmt  = get_statement($db,  $recent_films_query);    
    $hot_categories_stmt = get_statement($db, $hot_categories_query);       
?>

<main>
    <!-- Search Bar -->
    <form id="home-page-search" class="search-bar" action="/coms501/assignment/films.php" method="GET">
        <input type="text" id="searchbar" name="search" placeholder="Search for a film..." required>
        <button type="submit">
            <img src="/coms501/assignment/assets/images/magnifier.png" alt="Search" width=30 height=31>
        </button>
    </form>
    <!-- Recent Films Header -->
    <div class="parallax popcorn-image">
        <h2>Recent Films!</h2>
    </div>
    <!-- Recent Films -->
    <div class="carousel-container">
        <div class="recent-films-container">
            <?php
            // Build the film cards for each film retrieved
            while ($row = $recent_films_stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <!-- Form film card HTML -->
            <div class="recent-film">
                <a class="span-link" title="View this film" href="/coms501/assignment/film.php?id=<?php echo $row["film_id"] ?>">
                    <span></span>
                </a>
                <h3 class="card-title"><?php echo $row["title"]; ?></h3>
                <h4 class="card-description"><?php echo $row["description"]; ?></h4>
                <h2 class="card-price">Rental Price: £<?php echo $row["rental_rate"]; ?></h4>
                <img class="card-lang" src='./assets/images/langs/<?php echo $row["name"]; ?>.png' draggable="false">
                <img class="card-rating" src='./assets/images/ratings/<?php echo $row["rating"]; ?>.png' draggable="false">
            </div>
            <?php
            }
            ?>
        </div>
        <button id="carousel-button-prev" class="carousel-button" type="button" title="Previous" onclick="moveCarousel(-1);">
            <h1><</h1>
        </button>
        <button id="carousel-button-next" class="carousel-button" type="button" title="Next" onclick="moveCarousel(1);">
            <h1>></h1>
        </button>
    </div>
    <!-- Hot Categories Header -->
    <div class="parallax cinema-image">
        <h2>Hot Categories!</h2>
    </div>

    <!-- Hot Categories -->
    <div class="categories-container">
        <?php 
        // Build the category buttons
        while ($row = $hot_categories_stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <a class="link" href="/coms501/assignment/films.php?category=<?php echo $row["name"]; ?>">
            <div class="category" style="background-color: #<?php echo $row["color"] ?>; ">
                <h2 class="title"><?php echo $row["name"]; ?></h2>
                <div class="x-films-text"><?php echo $row["occurance"]; ?> films in this category!</div>
            </div>
        <?php
        }
        ?>
        </a>
    </div>
</main>
</body>
<script type="text/javascript">
    function moveCarousel(direction) {
        const recentFilmsContainer = document.querySelector('.recent-films-container');
        const index = parseInt(getComputedStyle(recentFilmsContainer).getPropertyValue('--index'));
        const newIndex = (index + direction + 3) % 3;
        recentFilmsContainer.style.setProperty('--index', newIndex);
    }
</script>
</html>