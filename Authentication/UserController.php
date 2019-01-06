<?php
include_once ('../Shop/CookieController.php');

class UserController
{

    public function __construct()
    {

    }

    public function UserController() {
        self::UserController();
    }

    /*
     * Add new User
     *
     * User in registration section use this method to create new user by secure hash password
     *
     * @param $name
     * @param $address
     * @param $phone
     * @param $email
     * @param $pass
     */
    function addNewUser($name, $address, $phone, $email, $pass){
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

    /*
    * Assign Session
    *
    * Assign the userid and username to session when user logged in
    *
    * @param $id
    * @param $name
    */
    function AssignSession($id,$name){
        $_SESSION['userID']= $id;
        $_SESSION['UserName']= $name;
    }

    /*
     * Login user
     *
     * Check the email and password
     *
     * @param $email
     * @param $pass
     * @return bool
     */
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
                    $cookieController->updateCookie($user->id);

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

    /*
     * Get user by ID
     *
     * get user data by passing the user id
     *
     * @param $id
     * @return object|stdClass
     */
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

    /*
     * Update User address
     *
     * before checkout the product user can change the address
     *
     * @param $id
     * @param $newAddress
     */
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


    /*
     * Check password
     *
     * in registration password should have enough complexity by having a different character
     *
     * @param $pwd
     * @return bool
     */
    public function checkPassword($pwd) {
        if(preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $pwd)){
            return true;
        }else {
            return false;
        }
    }

    /*
     * Is Email Available
     *
     * Check the email in registration that if is already exist stop the
     * registration process
     *
     * @param $email
     * @return bool
     */
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