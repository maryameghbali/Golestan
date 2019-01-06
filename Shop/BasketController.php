<?php
class BasketController
{

    public function __construct()
    {

    }

    public function BasketController(){
        self::__construct();
    }

    public function addToBasket($id_item, $quantity, $id_cookie){
        try {
            $sql = "insert into shop_basket (id_item, quantity, id_coockie) values (".$id_item.",".$quantity.",".$id_cookie.")";
            $link = DBConfig::getLink();

            if ($link->query($sql) === TRUE) {
                return "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $link->error;
            }
        }
        catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
                echo $e->getMessage();
                exit;
        } finally {
            $link->close();
        }

    }



    function getBasketCount($cookieValue) {
        if(isset($_POST) && isset($_COOKIE['UserBasket'])) {
            $cookie = $_COOKIE['UserBasket'];
            $cardArray = json_decode($cookie, true);
            return count($cardArray);
        }
        return '0';
    }

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

    public function deleteItemFromBasket($id) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare some teat address data
            $paymentId = 500;

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