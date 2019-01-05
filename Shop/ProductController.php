<?php

class ProductController
{
    public function __construct()
    {
    }

    public function ProductController(){
        self::ProductController();
    }

    /*
     * Add new product
     * each used sell their own item into the shop
     * */
    /**
     * @param $name
     * @param $des
     * @param $stock
     * @param $price
     * @param $userId
     */
    function addNewProduct($name, $des, $stock, $price, $userId) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('INSERT INTO shop_items (
            title,
            description,
            stock,
            price,
            user_id
        ) VALUES (
            ?,
            ?,
            ?,
            ?,
            ?
        );
    ');

            // Bind variables to a prepared statement as parameters
            $statement->bind_param('ssidi', $name, $des, $stock, $price, $userId);

            // Execute a prepared Query
            $statement->execute();

            // Close a prepared statement
            $statement->close();

            // Quick & "dirty" way to fetch newly created address id
            $id = $mysqli->insert_id;

            $fn=$_FILES['image']['tmp_name'];
            $path = "../assets/images/ProductImages/shop_items".$id.".jpg";
            move_uploaded_file($fn,$path);
            // Close database connection
            $mysqli->close();
        } catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }
    }


    /*
     * Get all products
     *
     * Index page show all the products that added by
     * each user
     * */
    function getAllProducts(){
        try {

            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('SELECT * FROM shop_items');

            // Execute a prepared query
            $statement->execute();

            // Gets a result set from a prepared statement
            $result = $statement->get_result();

            // Close a prepared statement
            $statement->close();

            // Close database connection
            $mysqli->close();

            // Fetch object from row/entry in result set
            return $result;
        } catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }
    }


    /*
     * Get all user Products
     *
     * Each user can get their own products
     * */
    function getAllUserProducts($userId){

        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('SELECT * FROM shop_items WHERE user_id = ?');

            // Binds variables to a prepared statement as parameters
            $statement->bind_param('i', $userId);

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


    /*
     * Delete Item
     *
     * Just user that added the item can delete it as well
     * */
    function deleteItem($userId, $itemId) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('DELETE FROM shop_items WHERE id = ? AND user_id = ?');

            // Bind variables to a prepared statement as parameters
            $statement->bind_param('ii', $itemId, $userId);

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


    /*
     * Get Product By productID
     *
     * find the product in database by passing the product id
     * */
    function getProductById($id){

        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('SELECT * FROM shop_items WHERE id = ?');

            // Binds variables to a prepared statement as parameters
            $statement->bind_param('i', $id);

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
}