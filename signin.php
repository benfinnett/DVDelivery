<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - DVDelivery</title>
    <link rel="icon" type="image/x-icon" href="./assets/images/favicon.ico">
    <link rel="stylesheet" href="style.css">
    
    <?php
    $error_messages = ["Username must be at least 1 character long, and less than 15 characters long.",
                       "Password must be at least 1 character long, and less than 20 characters long.",
                       "Username & password must contain only letters, numbers, spaces, and hyphens.",
                       "Username doesn't exist!",
                       "Incorrect password!"];
    ?>
</head>
<body>
    <div id="signin-bgimage"></div>
    <h3 id="signin-alert">Please view reflective report appendix for a sample of user login details.</h3>
    <div class="sign-in-form-container">
        <form id="sign-in-form" action="./actions/signin_auth.php" method="POST">
            <h1>Sign In</h1>
            <input type="text" id="username" class="credentials-box" name="username" placeholder="Username" onkeyup="validateUserInput();" required>
            <input type="password" id="current-password" class="credentials-box" name="current-password" placeholder="Password" onkeyup="validateUserInput();" autocomplete="current-password" required> 
            <input type="submit" id="submit-credentials" class="orange-button" value="Sign In" disabled>
            <?php
            if (isset($_GET["error"])) {
                echo "<h4 id='error-message' style='display: block; opacity: 1;'>" . $error_messages[intval($_GET["error"])] . "</h4>";
            } else {
                echo "<h4 id='error-message'></h4>";
            }
            ?>
        </form>
    </div>
</body>
<script type="text/javascript">
    function validateUserInput() {
        var username = document.getElementById("username").value;
        var password = document.getElementById("current-password").value;
        var error = document.getElementById("error-message");
        var submit = document.getElementById("submit-credentials");
        var allowed_characters = /^[a-zA-Z0-9 \-]*$/; // Letters, numbers, space & hyphen characters are allowed
        
        if ((username.match(allowed_characters) && password.match(allowed_characters))
            || (username === "" && password === "")) {
            // Enable the submit button if username and password are valid and not empty
            submit.disabled = (username === "" || password === "");
            // Hide error message
            error.style.opacity = "0";
            setTimeout(function() {
                error.style.display = "none";
            }, 200);
        } else {
            submit.disabled = true;
            error.style.display = "block";
            setTimeout(function() {
                error.textContent = "Username & password must contain only letters, numbers, spaces, and hyphens.";
                error.style.opacity = "1";
            }, 10);
        }
    }
</script>
</html>