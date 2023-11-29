<?php
// Include header & database
$page_title = "Manage - DVDelivery";
require_once("./includes/header.php");
require_once("./includes/database.php");
$db = connect_to_db();

// Send user to 404 page if they aren't a staff member
$username = $_COOKIE["user"];
if (isset($username)) {
    $user = new User($username);
    if (!$user->is_staff()) {
        header("Location: /coms501/assignment/404.php");
        exit();
    }
} else {
    header("Location: /coms501/assignment/404.php");
    exit();
}
?>
<main>
    <section class="info-container">
        <a class="link" href="/coms501/assignment/films.php">
            <div class="info-box">
                <h1>Films</h1>
                <h2>
                    <?php
                    $films_stmt = get_statement($db, "SELECT COUNT(*) as count FROM film");
                    echo $films_stmt->fetch()["count"];
                    ?>
                </h2>
            </div>
        </a>
        <a class="link" href="/coms501/assignment/categories.php">
            <div class="info-box">
                <h1>Categories</h1>
                <h2>
                    <?php
                    $categories_stmt = get_statement($db, "SELECT COUNT(*) as count FROM category");
                    echo $categories_stmt->fetch()["count"];
                    ?>
                </h2>
            </div>
        </a>
        <div class="info-box">
            <h1>Users</h1>
            <h2>
                <?php
                $users_stmt = get_statement($db, "SELECT COUNT(*) as count FROM user");
                echo $users_stmt->fetch()["count"];
                ?>
            </h2>
        </div>
    </section>
    <section class="forms-container">
        <!-- Add New Film -->
        <form id="add-film-form" action="./actions/add_film.php" method="POST">
            <legend>Add New Film</legend>
            
            <label for="add-film-name">Name Of Film</label>
            <input type="text" id="add-film-name" class="generic-input" name="film-name" placeholder="Fight Club" required>
            
            <label for="add-film-description">Film Description</label>
            <textarea id="add-film-description" class="generic-input" name="film-description" cols="30" rows="5" 
            placeholder="An insomniac office worker and a soap salesman form an underground fight club before spiralling into chaos." required></textarea>

            <label for="add-film-category">Film Category</label>
            <select id="add-film-category" class="generic-input" name="film-category" required>
                <?php
                $categories_stmt = get_statement($db, "SELECT name FROM category ORDER BY name ASC");
                
                while ($row = $categories_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $category = $row["name"];
                    echo "<option value='$category'>$category</option>";
                }                 
                ?>
            </select>
            
            <label for="add-film-year">Film Release Year</label>
            <input type="number" id="add-film-year" class="generic-input" name="film-year" min="1888" max="<?php echo date("Y"); ?>" placeholder="1999" required>
            
            <label for="add-film-lang">Film Language</label>
            <select id="add-film-lang" class="generic-input" name="film-lang" required>
                <?php
                $langs_stmt = get_statement($db, "SELECT name FROM language ORDER BY name ASC");
                
                while ($row = $langs_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $language = $row["name"];
                    echo "<option value='$language'>$language</option>";
                }                 
                ?>
            </select>
            
            <label for="add-film-rental-duration">Rental Duration In Days</label>
            <input type="number" id="add-film-rental-duration" class="generic-input" name="film-rental-duration" min="1" max="31" name="rental-duration" placeholder="7 days" required>

            <label for="add-film-rental-rate">Rental Rate Per Day</label>
            <input type="number" id="add-film-rental-rate" class="generic-input" name="film-rental-rate" step=".01" min="0.50" max="10" name="rental-rate" placeholder="£3.99" required>

            <label for="add-film-duration">Film Duration</label>
            <input type="number" id="add-film-duration" class="generic-input" name="film-duration" min="15" max="600" name="film-duration" placeholder="139 minutes" required>
        
            <label for="add-film-replacement">Film Repalcement Cost</label>
            <input type="number" id="add-film-replacement" class="generic-input" name="film-replacement" step=".01" min="5" max="40" name="repalcement-cost" placeholder="£19.99" required>
        
            <label for="add-film-rating">Film Rating</label>
            <select id="add-film-rating" class="generic-input" name="film-rating" required>
                <option value="G">G</option>
                <option value="PG">PG</option>
                <option value="PG-13">PG-13</option>
                <option value="R">R</option>
                <option value="NC-17">NC-17</option>
            </select>

            <fieldset>
                <legend id="film-special-features">Film Special Features</legend>
                <input type="checkbox" id="add-film-features-trailers" name="film-features[]" value="Trailers">
                <label for="add-film-features-trailers">Trailers</label>
                <input type="checkbox" id="add-film-features-commentaries" name="film-features[]" value="Commentaries">
                <label for="add-film-features-commentaries">Commentaries</label>
                <input type="checkbox" id="add-film-features-deleted" name="film-features[]" value="Deleted Scenes">
                <label for="add-film-features-deleted">Deleted Scenes</label>
                <input type="checkbox" id="add-film-features-behind" name="film-features[]" value="Behind the Scenes">
                <label for="add-film-features-behind">Behind the Scenes</label>
            </fieldset>
        
            <input type="submit" id="submit-new-film" class="orange-button" value="Add Film">
        </form>
        <!-- Add New Category -->
        <form id="add-category-form" action="./actions/add_category.php" method="POST">
            <legend>Add New Category</legend>
            
            <label for="add-category-name">Category Name:</label>
            <input type="text" id="add-category-name" class="generic-input" name="category-name" placeholder="Adventure" required>
            
            <label for="add-category-color">Category Color:</label>
            <input type="color" id="add-category-color" class="color-input" name="category-color" value="#86c2f3" required>
            
            <input type="submit" id="submit-new-category" class="orange-button" value="Add Category">
        </form>
    </section>
</main>
<?php 
// Handle success message
$success = $_GET["success"];
if (isset($success)) {
    // If successful, show success message
    echo "<footer>";
    if ($success === "false") {
        echo "<div id='success-message' class='success-message error'>";
        echo "<h2>Could not complete request! Does it already exist?</2>";
    } else {
        echo "<div id='success-message' class='success-message'>";
        echo "<h2>New $success successfully added!</2>";
    }
    echo "</div>";
    echo "</footer>";

    // Wait 2 seconds and hide success message
    echo "<script type='text/javascript'>";
    echo "
        const successMessage = document.getElementById('success-message');
        setTimeout(() => {
            successMessage.classList.add('show');
        }, 500);
        setTimeout(() => {
            successMessage.classList.remove('show');
        }, 4000);
    ";
    echo "</script>";
}
?>
</body>
<script type="text/javascript">
    // Animate the info box h2 numbers to count up to their value
    document.addEventListener("DOMContentLoaded", function() {
        const infoBoxesNumbers = document.querySelectorAll(".info-box > h2");

        infoBoxesNumbers.forEach(infoBoxNumber => {
            let startValue = 0;
            let endValue = parseInt(infoBoxNumber.textContent);
            let increment = Math.ceil(endValue / (2000 / 75)); // Calculates the increment value per 75 milliseconds

            let counter = setInterval(function() {
            startValue += increment;
            infoBoxNumber.textContent = startValue;

            if (startValue >= endValue) {
                infoBoxNumber.textContent = endValue;
                clearInterval(counter);
            }
            }, 75);
        });
    });
</script>
</html>