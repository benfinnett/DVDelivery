<?php
// include header and database
$page_title = "Films - DVDelivery";
require("./includes/header.php");
require_once("./includes/database.php");

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
                    ORDER BY film.title ASC";
    $params = ["%$search_query%"];
    $films_stmt = get_prepared_statement($db, $films_query . "\nLIMIT 15", $params);
    $number_of_rows = count_rows_prepared($db, $films_query, $params);
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
                    ORDER BY film.title ASC";
    $params = ["%$category%"];
    $films_stmt = get_prepared_statement($db, $films_query . "\nLIMIT 15", $params);
    $number_of_rows = count_rows_prepared($db, $films_query, $params);
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
                    ORDER BY film.title ASC";
    // Query the database and get the results
    $films_stmt = get_statement($db,  $films_query . "\nLIMIT 15");
    $number_of_rows = count_rows($db, $films_query);
}
?>

<main>
    <!-- Films Header -->
    <button id="return-button" type="button" title="Scroll to the top of the page" onclick="scrollToTop()">
        <svg width="30" height="30">
            <path d="m 5 25 l 10 -10" stroke-linecap="round" />
            <path d="m 25 25 l -10 -10" stroke-linecap="round" />
            <path d="m 5 15 l 10 -10" stroke-linecap="round" />
            <path d="m 25 15 l -10 -10" stroke-linecap="round" />
        </svg>
        <h2>Top</h2>
    </button>
    <div class="parallax film-compilation-image">
        <h2>Films</h2>
    </div>
    <!-- Filter -->
    <form id="films-page-search" class="search-bar" action="/coms501/assignment/films.php" method="GET">
        <input type="text" id="searchbar" name="search" placeholder="Search for a film..." required>
        <button type="submit">
            <img src="/coms501/assignment/assets/images/magnifier.png" alt="Search" width=30 height=31>
        </button>
    </form>
    <?php
    if (isset($search_query)) {
        if ($number_of_rows !== 1) {
            echo "<h3 id='filter-message'>" . $number_of_rows . " films match the search \"" . $search_query . "\"</h3>";
        } else {
            echo "<h3 id='filter-message'>" . $number_of_rows . " film matches the search \"" . $search_query . "\"</h3>";
        }
    } else if (isset($category)) {
        if ($number_of_rows !== 1) {
            echo "<h3 id='filter-message'>" . $number_of_rows . " films in the <span id='filter-message-category'>" . $category . "</span> category</h3>";
        } else {
            echo "<h3 id='filter-message'>" . $number_of_rows . " film in the <span id='filter-message-category'>" . $category . "</span> category</h3>";
        }
    }
    ?>

    <!-- Films List -->
    <div id="films-list-container">
        <?php
        // Build the film cards for each film retrieved
        while ($row = $films_stmt->fetch(PDO::FETCH_ASSOC)) {
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
        }
        ?>
    </div>
</main>
<footer>
    <div class="loading-ring-container" id="loading-ring-container">
        <div class="loading-ring"><div></div><div></div><div></div><div></div></div>
    </div>
</footer>
</body>
<script type="text/javascript">
    // Functionality for scroll to top button
    function scrollToTop() {
        document.documentElement.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

    function checkForShowButton(entry) {
        var film_box_number = 25;
        var nth_film_box = document.querySelector(`.film-box:nth-child(${film_box_number})`);
        var first_film_box = document.querySelector(".film-box:nth-child(1)");

        if (entry.isIntersecting && entry.target === nth_film_box) {
            scroll_position = window.scrollY;
            button.classList.add("show");
        } else if (!entry.isIntersecting && entry.target === nth_film_box
                 || entry.isIntersecting && entry.target === first_film_box) {
            if (window.scrollY < scroll_position) {
                button.classList.remove("show");
            }
            scroll_position = window.scrollY;
        }
    }

    var button = document.getElementById("return-button");
    var scroll_position = 0;
</script>

<script type="text/javascript">
    // Functionality for film box hover effect
    document.getElementById("films-list-container").onmousemove = e => {
        for(const box of document.getElementsByClassName("film-box")) {
            const rect = box.getBoundingClientRect(),
            x = e.clientX - rect.left,
            y = e.clientY - rect.top;

            box.style.setProperty("--mouse-x", `${x}px`);
            box.style.setProperty("--mouse-y", `${y}px`);
  };
}
</script>

<script type="text/javascript">
    // Functionality for infinite scroll pagination
    function getNewFilms(entry) {
        // Get last film box
        var last_film_box = document.querySelector(".film-box:last-of-type");

        // If the last film box is on-screen (in the viewport)
        if (entry.isIntersecting && entry.target === last_film_box) {
            var request = new XMLHttpRequest();

            // Open an async request to get_new_films.php
            var offset = document.querySelectorAll(".film-box").length

            // Hide loading ring if no new film boxes generated as that means all records have been displayed from query
            if (offset >= <?php echo $number_of_rows; ?>) {
                document.getElementById("loading-ring-container").style.display = "none";
                return;
            }
            var query_string = window.location.search;
            var search_params = new URLSearchParams(query_string);
            var search_value = search_params.get("search");
            var category_value = search_params.get("category");

            if (search_value !== null) {
                request.open("GET", "./actions/get_new_films.php?offset=" + offset + "&search=" + search_value, true);
            } else if (category_value !== null) {
                request.open("GET", "./actions/get_new_films.php?offset=" + offset + "&category=" + category_value, true);
            } else {
                request.open("GET", "./actions/get_new_films.php?offset=" + offset, true);
            }
            
            request.onload = function() {
                if (this.status >= 200 && this.status < 400) {
                    // Append data to page
                    var new_html = JSON.parse(this.response).html;
                    var new_film_boxes = Array.from(new DOMParser().parseFromString(new_html, "text/html").querySelectorAll(".film-box"));
                    var films_list_container = document.querySelector("#films-list-container");

                    new_film_boxes.forEach(
                        function(film_box) {
                            var new_div = document.createElement("div");
                            new_div.className = "film-box";
                            new_div.innerHTML = film_box.innerHTML;

                            films_list_container.appendChild(new_div);
                        }
                    );
                }
            }
            request.send();
        }
    }

    // Initiate IntersectionObserver
    var latest_film_box = null;
    var intersection_observer = new IntersectionObserver(
        function(entries) {
            entries.forEach(
                function(entry) {
                    if (entry.target.classList.contains("film-box")) {
                        if (entry.isIntersecting) {
                            getNewFilms(entry);
                        }
                        checkForShowButton(entry);
                    } 
                });
    });
    // Get all film-box divs
    var film_boxes = document.querySelectorAll(".film-box");

    // Start observing each film box for when it enters the viewport
    film_boxes.forEach(
        function(film_box) {
            intersection_observer.observe(film_box);
        }
    );

    // Create a MutationObserver to watch for changes to #films-list-container
    var films_list_observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            // Check if a new film box was added
            if (mutation.type === "childList") {
                mutation.addedNodes.forEach(function(added_node) {
                    if (added_node.nodeType === Node.ELEMENT_NODE && added_node.classList.contains("film-box")) {
                        // Start observing the new last film box
                        intersection_observer.observe(added_node);
                    }
                });
            }
        });
    });
    // Start observing #films-list-container for changes
    films_list_observer.observe(document.querySelector("#films-list-container"), { childList: true });
</script>
</html>