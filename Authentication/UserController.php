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
        $password = password_hash($pass,  PASSWORD_DEFAULT);
        try {
            $link =DBConfig::getLink();
            // Prepare an SQL statement for execution
            $statement = $link->prepare('INSERT INTO shop_user (
                    name,
                    address,
                    phone,
                    email,
                    password
                ) VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                );
            ');
            // Bind variables to a prepared statement as parameters
            $statement->bind_param('sssss', $name, $address, $phone, $email, $password);

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
    function AssignSession($id){
        $_SESSION['userID']= $id;
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
                $password=$user->password;
                if(password_verify($pass, $password)){
                    $this->AssignSession($user->id);
                    $cookieController = new CookieController();
                    $cookieController->AddCookieToDB($user->id);
                    return 'Welcome';
                }
                else
                {
                    return "Email or password is wrong";

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

}