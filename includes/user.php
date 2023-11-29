<?php
require_once("database.php");

class User {
    private $username;
    private $customer_id;
    private $staff_id;

    function __construct($username) {
        $this->username = $username;

        $user_query = "SELECT customer_id, staff_id
                       FROM user
                       WHERE username = '$username';";
        $user_stmt = get_statement(connect_to_db(),  $user_query);
        $user_data = $user_stmt->fetch();
        $this->customer_id = $user_data["customer_id"];
        $this->staff_id = $user_data["staff_id"];
    }

    function is_staff() {
        return !is_null($this->staff_id);
    }

    function get_username() {
        return $this->username;
    }

    function get_customer_id() {
        return $this->customer_id;
    }

    function get_staff_id() {
        return $this->staff_id;
    }
}

?>