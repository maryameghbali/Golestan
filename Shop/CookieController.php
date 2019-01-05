<?php
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
     * Add user selected items for order to cookie
     *
     * @param $cookie_value
     * @param $quantity
     */
    public function addToCookie($cookie_value, $quantity){
        if (!empty($_COOKIE['UserBasket'])) {
            $cookie = $_COOKIE['UserBasket'];
            $cardArray = json_decode($cookie, true);
            array_push($cardArray, [$cookie_value => $quantity]);
            $json = json_encode($cardArray);
        } else {
            $cardArray = array( [$cookie_value => $quantity]);
            $json = json_encode($cardArray);
        }
        setcookie("UserBasket", $json, time()+(3600*24*7), "/",null, null, true);
        if(isset($_SESSION['userID'])){
            $this->updateCookieToDb($_SESSION['userID'], $json);
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
    function AddCookieToDB($UId){

        $cookie = $_COOKIE['UserBasket'];
        $cardArray = json_decode($cookie, true);
        $json = json_encode($cardArray);
        $expirationDate = time()+(3600*24*7);
        $loggedin = isset($_SESSION['userID']) ? 1 : 0;

        $userController = new UserController();
        $result = $userController->getUserById($UId);
        if ($result!= null && $result!="")
        {
            $this->updateCookieToDb($UId, $json);

        }
        else
        {
            try {

                $sql = "insert into shop_cookie (cookie_value, id_user, loggedin, expiration_date) values ('".$json."',".$UId.",".$loggedin.",".$expirationDate.")";
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