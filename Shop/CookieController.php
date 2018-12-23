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

    public function addToCookie($cookie_value, $id_user, $expirationDate){
        try {
            $sql = "insert into shop_cookie (cookie_value, id_user, expiration_date) values ('".$cookie_value."',".$id_user.",".$expirationDate.")";
            $link = DBConfig::getLink();

            if ($link->query($sql) === TRUE) {
                //$MaxId = GetMaxId($link,"shop_cookie");
                $basketController = new BasketController();
                $basketController->addToBasket($cookie_value, 2, $id_user);
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