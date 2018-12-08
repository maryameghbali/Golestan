<?php

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

    function loginUser($email, $pass){
        try {
            $link = DBConfig::getLink();
            $statement = $link->prepare('SELECT * FROM shop_user WHERE email LIKE ?');
            $statement->bind_param('s',$email);
            $statement->execute();
            $result = $statement->get_result();

            while ($user = $result->fetch_object()) {
                // Output User info
                $password=$user->password;
                if(password_verify($pass, $password)){
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

}