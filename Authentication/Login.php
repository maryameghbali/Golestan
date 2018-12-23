<?php
include '../common/header.php';
include "../DBConfig.php";
include "UserController.php";

$mes ="";

// Create a key
if (empty($_SESSION['key'])) {
    $_SESSION['key'] = bin2hex(random_bytes(32));
}

// create CSRF token
$token = hash_hmac('sha256',"mySecretPath: index.php", $_SESSION['key']);

if(isset($_POST) & !empty($_POST)) {

    if (isset($_POST['login'])) 
    {
        if (hash_equals($token, $_POST['token'])) {
            $controller = new UserController();
            $mes = $controller->loginUser($_POST['inputEmail'],$_POST['inputPassword']);
        } else {
            $mes = "Validation failed";
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
                        <input type="hidden"  name="token" value="<?php echo $token ?>">
                        <p style="color: green;text-align: center"> <?php echo $mes?>

                        </p>
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