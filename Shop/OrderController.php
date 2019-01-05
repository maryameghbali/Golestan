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

    /*
     * Add new Order
     *
     * add order to table order by checking that item is available in last
     * step and if yes return the products that added to the order table
     * */
    function addNewOrder() {
        try {
            $orderStatus = array();
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();
            $cookie = $_COOKIE['UserBasket'];
            $productController = new ProductController();
            $cardArray = json_decode($cookie, true);

            foreach($cardArray as $products){
                foreach($products as $productId => $qua){
                    $rows = $productController->getProductById($productId);
                    while ($row=mysqli_fetch_array($rows)) {
                        $quantity = $qua;
                        $price = $row[4];
                        $id_item = $productId;
                        $id_user = $_SESSION['userID'];
                        $total_price = $row[4] * $quantity;
                        $itemStatus = $this->checkProductAvailable($id_item,$quantity);
                        if($itemStatus){
                            // Prepare an SQL statement for execution
                            $statement = $mysqli->prepare(
                                'INSERT INTO shop_order (quantity,price,id_item,id_user,total_price) 
                                    VALUES (?,?,?,?,?);');

                            // Bind variables to a prepared statement as parameters
                            $statement->bind_param('idiid', $quantity, $price, $id_item, $id_user, $total_price);

                            // Execute a prepared Query
                            $statement->execute();

                            // Close a prepared statement
                            $statement->close();

                            // Quick & "dirty" way to fetch newly created address id
                            $orderId = $mysqli->insert_id;
                            array_push($orderStatus,$orderId);
                            // Close database connection
                        }
                    }
                }
            }
            $mysqli->close();
            return $orderStatus;
        } catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }
    }


    /*
     * Check product is available
     *
     * Before add the product to order table we check the stock
     * */
    function checkProductAvailable($id_item,$quantity) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('SELECT * FROM shop_items WHERE id = ?');

            // Binds variables to a prepared statement as parameters
            $statement->bind_param('i', $id_item);

            // Execute a prepared query
            $statement->execute();

            // Gets a result set from a prepared statement
            $result = $statement->get_result();

            // Fetch object from row/entry in result set
            while ($item = $result->fetch_object()) {
                // Output customer info
                if($item->stock >= $quantity) {
                    $newStock = $item->stock - $quantity;
                    $this->updateItemStock($id_item, $newStock);
                    return true;
                } else {
                    return false;
                }
            }

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
     * Get all customer order
     *
     * In order view page, customer can see all the orders
     * */
    function getAllCustomerOrder($userId) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare(
                'SELECT * FROM shop_order INNER JOIN shop_items 
                        ON shop_items.id = shop_order.id_item
                        WHERE shop_order.id_user = ? ');

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


    /*
     * Update Item stock
     *
     * after order in placed need to update the stock in item table
     * */
    function updateItemStock($id, $quantity) {
        try {

            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('UPDATE shop_items SET stock = ? WHERE id = ?');

            // Bind variables to a prepared statement as parameters
            $statement->bind_param('ii', $quantity,$id );

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
     * Get order by Id
     *
     * in last step on checkout we get the all orders that successfully added to
     * order table
     * */
    function getOrderById($id) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();

            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare(
                'SELECT * FROM shop_order INNER JOIN shop_items 
                        ON shop_items.id = shop_order.id_item
                        WHERE shop_order.id = ? ');

            // Binds variables to a prepared statement as parameters
            $statement->bind_param('i', $id);

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