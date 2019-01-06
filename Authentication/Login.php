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
    function post_captcha($user_response) {
        $fields_string = '';
        $fields = array(
            'secret' => '6Ld3JocUAAAAANZPcv68OByGV_-3KOct3U3bsX0w',
            'response' => $user_response
        );
        foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    // Call the function post_captcha
    $res = post_captcha($_POST['g-recaptcha-response']);

    if (!$res['success']) {
        // What happens when the CAPTCHA wasn't checked
        $mes = "Please make sure you check the security CAPTCHA box.";
    } else {
        // If CAPTCHA is successfully completed...

        // Paste mail function or whatever else you want to happen here!
        echo '<br><p>CAPTCHA was completed successfully!</p><br>';

        if (isset($_POST['login']))
        {

            if (hash_equals($token, $_POST['token'])) {

                $controller = new UserController();
                $result = $controller->loginUser($_POST['inputEmail'],$_POST['inputPassword']);
                $source = $_GET['from'];
                if($result) {
                    echo "4444";
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
                        header('Location:/Golestan/Shop/Index.php');
                    }
                } else {
                    $mes = "Email or Password is wrong.";
                }
            } else {
                $mes = "Validation failed";
            }
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
                        <div class="g-recaptcha" data-sitekey="6Ld3JocUAAAAAATD1rUCNBeXlidj_WOox9MBT_Zn"></div>
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