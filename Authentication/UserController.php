<?php
include ('../Shop/CookieController.php');

class UserController
{

    public function __construct()
    {

    }

    public function UserController() {
        self::UserController();
    }

    function addNewUser($name, $address,$phone, $email, $pass){
        $salt = uniqid(mt_rand(), true);
        $passSalty=$pass.$salt;
        $password = password_hash($passSalty,  PASSWORD_DEFAULT);
        try {
            $link =DBConfig::getLink();
            // Prepare an SQL statement for execution
            $statement = $link->prepare('INSERT INTO shop_user (
                    name,
                    address,
                    phone,
                    email,
                    password,
                    salt
                ) VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                );
            ');
            // Bind variables to a prepared statement as parameters
            $statement->bind_param('ssssss', $name, $address, $phone, $email, $password, $salt);

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
    function AssignSession($id,$name){
        $_SESSION['userID']= $id;
        $_SESSION['UserName']= $name;
        $_SESSION['start'] = time(); // Taking now logged in time.
        // Ending a session in 30 minutes from the starting time.
        $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
    }

    function loginUser($email, $pass){
        try {
            $link = DBConfig::getLink();
            $statement = $link->prepare('SELECT * FROM shop_user WHERE email = ?');
            $statement->bind_param('s',$email);
            $statement->execute();
            $result = $statement->get_result();

            while ($user = $result->fetch_object()) {
                // Output User info
                $salt = $user->salt;
                $password=$user->password;
                $preparedPass = $pass.$salt;

                if(password_verify($preparedPass, $password)){
                    $this->AssignSession($user->id,$user->name);
                    $cookieController = new CookieController();
                    if (!empty($_COOKIE['UserBasket']))
                    {
                         $cookieController->AddCookieToDB($user->id);
                    }
                    else
                    {
                        $result = $cookieController->getCookieByUserId($user->id);
                        if ($result!= null && $result!="")
                        {
                            if ($result->loggedin == 0)
                                setcookie("UserBasket", $result->cookie_value,$result->expiration_date	, "/",null, null, true);
                            $cookieController->updateLoggedInToDb($user->id,1);
                        }


                    }
                    return true;
                }
                else
                {
                    return false;

                }
            }
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

    function getUserById($id) {
        try {
            $link = DBConfig::getLink();
            $statement = $link->prepare('SELECT * FROM shop_user WHERE id = ?');
            $statement->bind_param('s',$id);
            $statement->execute();
            $result = $statement->get_result();
            $user = $result->fetch_object();
            return $user;
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

    function updateUserAddress($id, $newAddress){
        try {
            // Open a new connection to the MySQL server
            $link = DBConfig::getLink();
            echo $id;
            echo $newAddress;
            // Prepare some teat address data
            $address = $newAddress;
            $addressId = $id;

            // Prepare an SQL statement for execution
            $statement = $link->prepare('UPDATE shop_user SET address = ? WHERE id = ?');

            // Bind variables to a prepared statement as parameters
            $statement->bind_param('si', $address, $addressId);

            // Execute a prepared Query
            $statement->execute();

            // Close a prepared statement
            $statement->close();

            // Close database connection
            $link->close();
        } catch (mysqli_sql_exception $e) {
            // Output error and exit upon exception
            echo $e->getMessage();
            exit;
        }
    }

    public function checkPassword($pwd) {
        if(preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $pwd)){
            return true;
        }else {
            return false;
        }
    }

    
    function isEmailAvailable($email) {
        try {
            $link = DBConfig::getLink();
            $statement = $link->prepare('SELECT * FROM shop_user WHERE email = ?');
            $statement->bind_param('s',$email);
            $statement->execute();
            $result = $statement->get_result();
            while ($user = $result->fetch_object()) {
                if($user->email){
                    return true;
                }
            }
            return false;
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