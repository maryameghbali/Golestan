<?php

include_once ('../Shop/BasketController.php');
class CookieController
{
    public function __construct()
    {
    }

    public function CookieController(){
        self::CookieController();
    }

    /*
     * Add to Cookie
     *
     * If no SessionId, then add SessionId to cookie and add user selected items to Basket table in database
     * Else just add user selected items to Basket table in database
     * @param $cookie_value
     * @param $quantity
     */
    public function addToCookie($productId, $quantity)
    {
        $basketController = new BasketController();
        if (isset($_COOKIE['SessionId'])) {
            $CookieValue = $_COOKIE['SessionId'];
            $result = $this->getCookieByValue($CookieValue);
            $CookieId = $result->id;
            $basketController->addToBasket($productId,$quantity,$CookieId);

        } else {
            $CookieValue = bin2hex(random_bytes(32));
            setcookie("SessionId", $CookieValue, time()+(3600*24*7), "/",null, null, true);
            $userId = isset($_SESSION['userID']) ? $_SESSION['userID'] : -1;
            $this->AddCookieToDB($CookieValue,$userId,0);
            $result = $this->getCookieByValue($CookieValue);
            $CookieId = $result->id;
            $basketController->addToBasket($productId,$quantity,$CookieId);
        }
    }

    /*
    * Update Cookie
    *
    * Update Cookie SessionId with new value after user logged in
    * This method run after user logged in to system and add new cookie value with flag Loggedin equal one
    *
    * @param $UId
    */
    public function updateCookie($UId)
    {
        if (isset($_COOKIE['SessionId'])) {
            $CookieValue = bin2hex(random_bytes(32));
            $OldCookieValue = $_COOKIE['SessionId'];
            $this->updateCookieToDbByValue($OldCookieValue,$CookieValue,$UId,1);
            setcookie("SessionId", $CookieValue, time()+(3600*24*7), "/",null, null, true);
        }
        else
        {
            $CookieValue = bin2hex(random_bytes(32));
            $this->AddCookieToDB($CookieValue,$UId,1);
            setcookie("SessionId", $CookieValue, time()+(3600*24*7), "/",null, null, true);
        }


    }

    /*
   * Update Cookie To Db By Value
   *
   * Update Cookie with new value
   *
   * @param $OldCookieValue
   * @param $CookieValue
   * @param $userId
   * @param $LoggedIn
   */
    public function updateCookieToDbByValue($OldCookieValue,$CookieValue,$userId,$LoggedIn) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();


            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('UPDATE shop_cookie SET cookie_value = ? , id_user = ? ,loggedin = ?    WHERE cookie_value = ?');

            // Bind variables to a prepared statement as parameters
            $statement->bind_param('siis', $CookieValue, $userId,$LoggedIn,$OldCookieValue);

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
         * Delete Cookie
         *
         * Delete user selected item from cookie
         *
         * @param $cookie_value
         */
    public function deleteCookie($cookie_value) {
        
        $newArray = [];
        $cookie = $_COOKIE['UserBasket'];
        $cardArray = json_decode($cookie, true);
        foreach ($cardArray as $products) {
            foreach ($products as $productId => $quantity) {
                if($productId != $cookie_value) {
                    array_push($newArray, [$productId => $quantity]);
                }
            }
        }
        if (!empty($newArray))
            $json = json_encode($newArray);
        setcookie("UserBasket", $json, time()+(3600*24*7), "/",null, null, true);

        if(isset($_SESSION['userID'])){
            $this->updateCookieToDb($_SESSION['userID'], $json);
        }
    }

    function deleteCookies() {
        setcookie("UserBasket", "", time()-(3600*24*7), "/",null, null, true);
    }

    /*
     *  Add Cookie To Database
     *
     * Add Cookie items for order to database after user logged in
     *
     * @param $UId
     */
    function AddCookieToDB($CookieValue,$UId,$LoggedIn){


        $expirationDate = time()+(3600*24*7);
        $loggedin = isset($_SESSION['userID']) ? 1 : 0;
        try {
            $link =DBConfig::getLink();
            // Prepare an SQL statement for execution
            $statement = $link->prepare('INSERT INTO shop_cookie (
                    cookie_value,
                    id_user,
                    loggedin,
                    expiration_date
                    
                ) VALUES (
                    ?,
                    ?,
                    ?,
                    ?
                   
                );
            ');
            // Bind variables to a prepared statement as parameters
            $statement->bind_param('siii', $CookieValue, $UId, $LoggedIn, $expirationDate);

            // Execute a prepared Query
            $statement->execute();

            // Close a prepared statement
            $statement->close();

            $link->close();

        } catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }

    }

    /*
   *  update Cookie To database
   *
   * Update database for cookie table when cookie changed by user
   *
   * @param $userId
   * @param $value
   */
    public function updateCookieToDb($userId, $value) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();


            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('UPDATE shop_cookie SET cookie_value = ? , loggedin = 1   WHERE id_user = ?');

            // Bind variables to a prepared statement as parameters
            $statement->bind_param('si', $value, $userId);

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
    *  update LoggedIn To database
    *
    * Update database for LoggedIn column in cookie table when user logged in or logged out
    *
    * @param $userId
    * @param $LoggedIn
    */
    public function updateLoggedInToDb($userId,$LoggedIn) {
        try {
            // Open a new connection to the MySQL server
            $mysqli = DBConfig::getLink();


            // Prepare an SQL statement for execution
            $statement = $mysqli->prepare('UPDATE shop_cookie SET loggedin = ? WHERE id_user = ?');

            // Bind variables to a prepared statement as parameters
            $statement->bind_param('ii', $LoggedIn, $userId);

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

    function getCookieByValue($value) {
        try {
            $link = DBConfig::getLink();
            $statement = $link->prepare('SELECT * FROM shop_cookie WHERE cookie_value = ?');
            $statement->bind_param('s',$value);
            $statement->execute();
            $result = $statement->get_result();
            $cookie = $result->fetch_object();
            return $cookie;
        }
        catch(mysqli_sql_exception $e){
            echo $e->getMessage(), PHP_EOL;
            exit();
        }
        finally
        {
            $link->close();
        }
    }
    /*
    * get Cookie By UserId
    *
    * Get the cookie value for specific user from database
    *
    * @param $userId
    */
    function getCookieByUserId($userId){
        try {
            $link = DBConfig::getLink();
            $statement = $link->prepare('SELECT * FROM shop_cookie WHERE id_user = ?');
            $statement->bind_param('s',$userId);
            $statement->execute();
            $result = $statement->get_result();
            $cookie = $result->fetch_object();
            return $cookie;
        }
        catch(mysqli_sql_exception $e){
            echo $e->getMessage(), PHP_EOL;
            exit();
        }
        finally
        {
            $link->close();
        }
    }

}