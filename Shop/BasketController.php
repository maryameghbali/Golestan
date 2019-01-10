<?php

class BasketController
{
    public function __construct()
    {

    }

    public function BasketController(){
        self::__construct();
    }

    /**
     * @param $id_item
     * @param $quantity
     * @param $id_cookie
     * @return string
     */
    public function addToBasket($id_item, $quantity, $id_cookie){

        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('INSERT INTO shop_basket (
                id_item,
                quantity,
                id_coockie
                ) VALUES (
                    ?,
                    ?,
                    ?
                );
            ');

            // Bind variables to a prepared statement as parameters
            $statement->bind_param('iii', $id_item, $quantity, $id_cookie);

            // Execute a prepared Query
            $statement->execute();

            // Close a prepared statement
            $statement->close();

            // Quick & "dirty" way to fetch newly created address id
            $basketId = $mysqli->insert_id;

            // Close database connection
            $mysqli->close();
            return $basketId;
        } catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }

    }

    /**
     * @param $cookieValue
     * @return bool|mysqli_result
     */
    public function getItemsFromBasket($cookieValue) {
       try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('SELECT * FROM shop_basket 
                    INNER JOIN shop_cookie ON shop_cookie.id = shop_basket.id_coockie 
                    INNER JOIN shop_items ON shop_basket.id_item = shop_items.id 
                    WHERE shop_cookie.cookie_value = ?');

            // Binds variables to a prepared statement as parameters
            $statement->bind_param('s', $cookieValue);

            // Execute a prepared query
            $statement->execute();

            // Gets a result set from a prepared statement
            $result = $statement->get_result();

            // Close a prepared statement
            $statement->close();

            // Close database connection
            $mysqli->close();

            return $result;
        } catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * @param $cookieValue
     * @return bool|mysqli_result
     */
    public function getCountOfItemsFromBasket($cookieValue) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('SELECT count(*) FROM shop_basket 
                    INNER JOIN shop_cookie ON shop_cookie.id = shop_basket.id_coockie 
                    INNER JOIN shop_items ON shop_basket.id_item = shop_items.id 
                    WHERE shop_cookie.cookie_value = ?');

            // Binds variables to a prepared statement as parameters
            $statement->bind_param('s', $cookieValue);

            // Execute a prepared query
            $statement->execute();

            // Gets a result set from a prepared statement
            $result = $statement->get_result();

            // Close a prepared statement
            $statement->close();

            // Close database connection
            $mysqli->close();

            return $result;
        } catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * @param $id
     */
    public function deleteItemFromBasket($id) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('DELETE FROM shop_basket WHERE id = ?');

            // Bind variables to a prepared statement as parameters
            $statement->bind_param('i', $id);

            // Execute a prepared Query
            $statement->execute();

            // Close a prepared statement
            $statement->close();

            // Close database connection
            $mysqli->close();
        } catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * @param $id
     */
    public function deleteItemFromBasketById($id) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();


            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('DELETE FROM shop_basket WHERE id = ?');

            // Bind variables to a prepared statement as parameters
            $statement->bind_param('i', $id);

            // Execute a prepared Query
            $statement->execute();

            // Close a prepared statement
            $statement->close();

            // Close database connection
            $mysqli->close();
        } catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }
    }
}