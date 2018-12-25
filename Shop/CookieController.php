<?php
include ('BasketController.php');
class CookieController
{
    public function __construct()
    {
    }

    public function CookieController(){
        self::CookieController();
    }

    public function addToCookie($cookie_value, $id_user){

            if (!empty($_COOKIE['UserBasket'])) {
                $cookie = $_COOKIE['UserBasket'];
                $cardArray = json_decode($cookie, true);
                array_push($cardArray, [$cookie_value => "3"]);
                $json = json_encode($cardArray);
            } else {

                $cardArray = array( [$cookie_value => "1"]);
                $json = json_encode($cardArray);

            }
            setcookie("UserBasket", $json, time()+3600, "/","", 0);
    }
    // public function addToCookie(){
    //     try {
    //         $cookie_value = "a";
    //         $id_user = 1;
    //         $loggedin = 1;
    //         $expirationDate = 1545585968;
    //         $link = DBConfig::getLink();
    //         // Prepare an SQL statement for execution
    //         $statement = $link->prepare('INSERT INTO shop_cookie (
    //                 cookie_value,
    //             ) VALUES (
    //                 ?,
    //             );
    //         ');
    //         // Bind variables to a prepared statement as parameters
    //         $statement->bind_param('s', $cookie_value);
            
    //         // Execute a prepared Query
    //         $statement->execute();

    //         // Close a prepared statement
    //         $statement->close();

    //         $link->close();
    //     } catch (mysqli_sql_exception $e) {
    //         // Output error and exit upon exception
    //         echo $e->getMessage();
    //         exit;
    //     }
    // }

    function getAllCookies($userId){
        try{
            $sql = "SELECT shop_items.id, shop_items.title, shop_items.price, shop_basket.quantity FROM shop_basket INNER JOIN shop_items ON shop_items.id = shop_basket.id_item WHERE shop_basket.id_coockie ='$cookie'";
            $link = DBConfig::getLink();
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