<?php
include 'header.php';
include "DBConfig.php";

if(isset($_POST) & !empty($_POST)) {

    if (isset($_POST['register'])) 
    {
        $password = password_hash($_POST['inputPassword'], PASSWORD_DEFAULT);
        try {   
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
            $statement->bind_param('sssss', $_POST['inputName'], $_POST['inputAddress'], $_POST['inputPhone'], $_POST['inputEmail'], $password);
            
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
}
?>

<div class="container">
    <div class="card">
        <h5 class="card-header">Register</h5>
        <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label for="inputName">Full Name</label>
                <input type="text" class="form-control" name="inputName" placeholder="Enter Your Name">
            </div>
            <div class="form-group">
                <label for="inputAddress">Address</label>
                <textarea class="form-control" name="inputAddress" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label for="inputPhone">Phone</label>
                <input type="text" class="form-control" name="inputPhone" placeholder="Phone number">
            </div>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="email" class="form-control" name="inputEmail" aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" class="form-control" name="inputPassword" placeholder="Password">
            </div>

            <button type="submit" class="btn btn-primary" name="register">Submit</button>
            </form>
            <a href="Registration.php">I already have an account.</a>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>