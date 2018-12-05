<?php
include '../common/header.php';
include "../DBConfig.php";
$mes ="";
if(isset($_POST) & !empty($_POST)) {

    if (isset($_POST['login'])) 
    {
        try {
            $statement = $link->prepare('SELECT * FROM shop_user WHERE email LIKE ?');
            $statement->bind_param('s',$_POST['inputEmail']);
            $statement->execute();
            $result = $statement->get_result();

            while ($user = $result->fetch_object()) {
                // Output User info
                $password=$user->password;
                if(password_verify($_POST['inputPassword'], $password)){
                    $mes =  'Welcome';
                }
                else
                {
                    $mes = "Email or password is wrong";

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
?>
    <div class="container fill_height" style="padding-top: 100px">
        <div class="row align-items-center fill_height">
            <div class="col-md-6">
                <div class="card">
                    <h5 class="card-header">Login</h5>
                    <div class="card-body">
                    <form method="POST">
                        <p style="color: green;text-align: center"> <?php echo $mes?></p>
                        <div class="form-group">
                            <label for="inputEmail">Email address</label>
                            <input type="email" class="form-control" name="inputEmail" aria-describedby="emailHelp" placeholder="Enter email">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">Password</label>
                            <input type="password" class="form-control" name="inputPassword" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary" name="login">Submit</button>
                        </form>
                        <a href="Registration.php">Do not have an account</a>
                    </div>
                </div>
                </div>
        </div>
    </div>
    </div>
<?php
include '../common/footer.php';
?>