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
  
<img width="960" alt="image" src="https://github.com/benfinnett/DVDelivery/assets/125909754/4ed745e3-8cc2-485d-b46f-dbf89f70239c">
<img width="960" alt="image" src="https://github.com/benfinnett/DVDelivery/assets/125909754/7a3d0fac-cab2-4480-84e9-a9a866da117b">

## Film Categories Page

<img width="960" alt="image" src="https://github.com/benfinnett/DVDelivery/assets/125909754/204d37d7-f071-45a4-abdc-dbe5075a17ef">

## Films List Page

<img width="960" alt="image" src="https://github.com/benfinnett/DVDelivery/assets/125909754/0205c1e9-636a-4b01-ad29-fc123be84184">

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
