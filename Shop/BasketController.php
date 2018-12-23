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
        // try {
        //     $link = DBConfig::getLink();
        //     // Prepare an SQL statement for execution
        //     $statement = $link->prepare('INSERT INTO shop_basket (
        //             id_item,
        //             quantity,
        //             id_coockie,
        //         ) VALUES (
        //             ?,
        //             ?,
        //             ?,
        //         );
        //     ');
        //     // Bind variables to a prepared statement as parameters
        //     $statement->bind_param('iii', $id_item, $quantity, $id_cookie);

        //     // Execute a prepared Query
        //     $statement->execute();

        //     // Close a prepared statement
        //     $statement->close();

        //     $link->close();
        // } catch (mysqli_sql_exception $e) {
        //     // Output error and exit upon exception
        //     echo $e->getMessage();
        //     exit;
        // }
    }

    function getAllBasketItems($cookie){
        try{
            $sql = "SELECT shop_items.id, shop_items.title, shop_items.price, shop_basket.quantity FROM shop_basket INNER JOIN shop_items ON shop_items.id = shop_basket.id_item WHERE shop_basket.id_coockie ='$cookie'";
            $link = \DBConfig::getLink();
            $response = mysqli_query($link,$sql);
            $link->close();
            return $response;
        }
        catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }
    }
}