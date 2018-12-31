<?php
session_start();
include "../DBConfig.php";
include "UserController.php";

$mes ="";
$basketPage = '/Golestan/Shop/checkout.php';
$productManage = '/Golestan/Shop/ProductManage.php';
$viewOrders = '/Golestan/Shop/viewOrders.php';
// Create a key
if (empty($_SESSION['key'])) {
    $_SESSION['key'] = bin2hex(random_bytes(32));
}

// create CSRF token
$token = hash_hmac('sha256',"mySecretPath: index.php", $_SESSION['key']);
$_SESSION['token'] = $token;
if(isset($_POST) & !empty($_POST)) {

    if (isset($_POST['login']))
    {
        if (hash_equals($token, $_POST['token'])) {
            $controller = new UserController();
            $result = $controller->loginUser($_POST['inputEmail'],$_POST['inputPassword']);
            $source = $_GET['from'];
            if($result) {
                if($source == "'checkout'") 
                {
                    header('Location: '.$basketPage);
                }
                elseif ($source == "'addProduct'")
                {
                    header('Location: '.$productManage);
                }
                elseif ($source == "'orders'")
                {
                    header('Location: '.$viewOrders);
                }
                else {
                    header('Location:/Golestan/Index.php');
                }
            } else {
                $mes = "Email or Password is wrong.";
            }
        } else {
            $mes = "Validation failed";
        }

    }

}
include '../common/header.php';



?>
    <div class="container fill_height" style="padding-top: 100px">
        <div class="row align-items-center fill_height">
            <div class="col-md-6">
                <div class="card">
                    <h5 class="card-header">Login</h5>
                    <div class="card-body">
                    <form method="POST">
                        <p style="color: green;text-align: center"> <?php echo $mes?>
                        <input type="hidden"  name="token" value="<?php echo $token ?>">
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