<?php

class ProductController
{
    public function __construct()
    {
    }

    public function ProductController(){
        self::ProductController();
    }

    function addNewProduct($name, $des, $stock, $price, $userId) {
        $sql = "insert into shop_items (title,description,stock,price, user_id) values ('".$name."','".$des."',".$stock.",".$price.",".$userId.")";
        $link = DBConfig::getLink();

        if ($link->query($sql) === TRUE) {

            $fn=$_FILES['image']['tmp_name'];
            $MaxId = GetMaxId($link,"shop_items");
            $path = "../assets/images/ProductImages/shop_items".$MaxId.".jpg";
            move_uploaded_file($fn,$path);
            return "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }

        $link->close();
    }

    function getAllProduction(){
        $sql = "select *  from shop_items";
        $link = DBConfig::getLink();
        $result=mysqli_query($link,$sql);
        $link->close();
        return $result;
    }

    function getAllUserProduction($userId){

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
}