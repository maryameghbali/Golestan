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



    function getBasketCount() {
        if(isset($_POST) && isset($_COOKIE['UserBasket'])) {
            $cookie = $_COOKIE['UserBasket'];
            $cardArray = json_decode($cookie, true);
            return count($cardArray);
        }
        return '0';
    }
}