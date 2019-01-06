<?php
include("ProductController.php");
//include("BasketController.php");
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
     *
     * @return array
     */
    function addNewOrder($sessionValue) {
        try {
            $orderStatus = array();
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();
                $basketController = new BasketController();
                $result = $basketController->getItemsFromBasket($sessionValue);
                    while ($row=mysqli_fetch_array($result)) {
                        $id_basket = $row[0];
                        $quantity = $row[2];
                        $price = $row[13];
                        $id_item = $row[9];
                        $id_user = $row[6];
                        $total_price = $row[13] * $quantity;
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

                            $basketController->deleteItemFromBasketById($id_basket);

                            // Quick & "dirty" way to fetch newly created address id
                            $orderId = $mysqli->insert_id;
                            array_push($orderStatus,$orderId);
                            // Close database connection
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
     *
     * @param $id_item
     * @param $quantity
     * @return bool
     */
    function checkProductAvailable($id_item, $quantity) {
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
     *
     * @param $userId
     * @return bool|mysqli_result
     */
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
     *
     * @param $id
     * @param $quantity
     */
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
     *
     * @param $id
     * @return bool|mysqli_result
     */
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