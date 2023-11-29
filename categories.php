<?php
// include header and database
$page_title = "Categories - DVDelivery";
require("./includes/header.php");
require_once("./includes/database.php");

$categories_query = "SELECT category.name, category.color, COUNT(film_category.category_id) AS `occurrence`
                     FROM category
                     LEFT JOIN film_category
                     ON category.category_id = film_category.category_id
                     GROUP BY category.category_id
                     ORDER BY category.name ASC;";

$db = connect_to_db();
$categories_stmt = get_statement($db, $categories_query);
?>
<main>
    <div class="parallax clapperboard-image">
        <h2>Categories</h2>
    </div>
    <div class="categories-container">
        <?php 
        // Build the category buttons
        while ($row = $categories_stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <a class="link" href="/coms501/assignment/films.php?category=<?php echo $row["name"]; ?>">
            <div class="category" style="background-color: #<?php echo $row["color"] ?>; ">
                <h2 class="title"><?php echo $row["name"]; ?></h2>
                <div class="x-films-text"><?php echo $row["occurrence"]; ?> films in this category!</div>
            </div>
        </a>
        <?php
        }
        ?>
    </div>
</main>
</body>
</html>