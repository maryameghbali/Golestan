<?php
include("ProductController.php");

class OrderController
{
    public function __construct()
    {
    }

    public function OrderController(){
        self::OrderController();
    }

    function addNewOrder() {
        try {

            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();
            $cookie = $_COOKIE['UserBasket'];
            $productController = new ProductController();
            $cardArray = json_decode($cookie, true);

            foreach($cardArray as $products){
                foreach($products as $productId => $qua){
                    $rows = $productController->getProdcutById($productId);
                    while ($row=mysqli_fetch_array($rows)) {
                        $quantity = $qua;
                        $price = $row[4];
                        $id_item = $productId;
                        $id_user = $_SESSION['userID'];
                        $total_price = $row[4] * $quantity;
                        // Prepare an SQL statement for execution
                        $statement = $mysqli->prepare('INSERT INTO shop_order (quantity,price,id_item,id_user,total_price) VALUES (?,?,?,?,?);');

                        // Bind variables to a prepared statement as parameters
                        $statement->bind_param('idiid', $quantity, $price, $id_item, $id_user, $total_price);

                        // Execute a prepared Query
                        $statement->execute();

                        // Close a prepared statement
                        $statement->close();

                        // Quick & "dirty" way to fetch newly created address id
                        $orderId = $mysqli->insert_id;

                        // Close database connection

                    }
                }
            }
            $mysqli->close();
        } catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }
    }

    function getAllCustomerOrder($userId) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('SELECT * FROM shop_order INNER JOIN shop_items ON shop_items.id = shop_order.id_item WHERE shop_order.id_user = ? ');

            // Binds variables to a prepared statement as parameters
            $statement->bind_param('i', $userId);

            // Execute a prepared query
            $statement->execute();

            // Gets a result set from a prepared statement
            $result = $statement->get_result();

            // Fetch object from row/entry in result set
            return $result;

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