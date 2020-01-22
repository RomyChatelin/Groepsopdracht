<?php
    // A class that contains user related properties and methods.
    class User {
        // Properties.
        private $user;
        private $pass;
        private $dbh;

        // Constructor: method to create objects.
        function __construct($user, $pass, $db_handle) {
            $this->user = $user;
            // Hash passwords.
            $this->pass = md5($pass);
            $this->dbh = $db_handle;
        }

        // Method to create a user account. IMPORTANT: use Utility::findOjbect() before calling createAccount() else id's get claimed when entered username already exists.
        function createAccount($details) {
                try {
                    // Create variables to insert into the query.
                    $columns = array();
                    $values = array();
                    $placeholders = array();
                    // Divide the details array into column-value pairs and push values into arrays.
                    foreach($details as $column => $value) {
                        array_push($columns, $column);
                        array_push($values, $value);
                        array_push($placeholders, '?');
                    }
                    // Set up a prepared statement: implode function helps transforming arrays into strings. 
                    $sql = "INSERT INTO users (".implode(',', $columns).") VALUES (".implode(',', $placeholders).")";
                    $stmt = $this->dbh->prepare($sql);
                    // Replace placeholders for values.
                    for ($i = 0; $i<(count($placeholders)); $i++) {
                        // Replace pass for hashed pass.
                        if ($columns[$i] === 'hashed_pass') {
                            $stmt->bindValue(($i+1), $this->pass);
                        } else {
                            $stmt->bindValue(($i+1), $values[$i]);
                        }
                    }                    
                    // Execute statement.
                    $stmt->execute();
                // FIX ME -> CHANGE EXCEPTION MESSAGE
                } catch(PDOException $e) {
                    echo $e->getMessage();
                }
        }

        // Method to return username
        function getUsername() {
            return $this->user;
        }

        // Method to return user password
        function getPassword() {
            return $this->pass;
        }
    }
?>