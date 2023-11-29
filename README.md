# COMS501-DVD--Rentals
A web application to simulate a DVD rental shop's website, created for my second year web application development module at University.

# Cloning Repo
A `config.php` file must be created within the `includes` directory contianing the login credentials for the database. This can be done using the following code and entering the correct details into the blank quotations:
```php
<?php
// Credentials to the database. Ensure that this file is excluded in the .gitignore file.
define("DB_HOST", ""); // Database hostname
define("DB_NAME", ""); // Database name
define("DB_USER", ""); // Database username
define("DB_PASS", ""); // Database user password
?>
```
The database used is a modified version of the [Sakila](https://dev.mysql.com/doc/sakila/en/) database with added tag(tag_id, name) and film_tag(film_id, tag_id) tables.

# Debug
If PHP code needs debugging, please insert the following code to enable error display:
```php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```