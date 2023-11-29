# DVDelivery Website
A website for a fictional DVD rental shop, created for my second year web application development module at University.

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

# Images
<details>
<summary>Click to view website images</summary>
  
## Homepage
  
![image](https://github.com/benfinnett/DVDelivery/assets/125909754/19eb810a-e3b5-4211-93a3-b9c2f1b34174)
![image](https://github.com/benfinnett/DVDelivery/assets/125909754/d86154ff-be03-405f-ae9e-04a224078b48)
## Film Categories Page

![image](https://github.com/benfinnett/DVDelivery/assets/125909754/35c80509-5e2e-4528-b665-47659c9b236c)
## Films List Page

![image](https://github.com/benfinnett/DVDelivery/assets/125909754/f7ad1430-d052-4c84-9068-2a42c28772c2)
## Sign In Page

![image](https://github.com/benfinnett/DVDelivery/assets/125909754/d4fb54ed-cc0b-4270-8223-d483fe7d8ca1)
## Staff Manage Page

![image](https://github.com/benfinnett/DVDelivery/assets/125909754/7b0be435-3aac-4771-a84a-7fb746dff2e8)
</details>


# Debug
If PHP code needs debugging, please insert the following code to enable error display:
```php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```
