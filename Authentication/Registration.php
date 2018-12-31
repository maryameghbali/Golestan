<?php
include '../common/header.php';
include "../DBConfig.php";
include "UserController.php";
$passError = "";
$emailControl = "";
if(isset($_POST) & !empty($_POST)) {

    if (isset($_POST['register'])) 
    {
        $controller = new UserController();
        $checkEmail = $controller->isEmailAvailable($_POST['inputEmail']);
        $isPassStrong = $controller->checkPassword($_POST['inputPassword']);
        if($checkEmail){
            $emailControl ="This email has aleady registered!";
        }
        elseif(!$isPassStrong) {
            $passError = "Your password should combination of number, Capital and small character and also symbols.";
        } else {
            $controller->addNewUser($_POST['inputName'], $_POST['inputAddress'],
            $_POST['inputPhone'], $_POST['inputEmail'], $_POST['inputPassword']);
        }
        
    }
}
?>

    <div class="container fill_height" style="padding-top: 200px">
        <div class="row align-items-center fill_height">
            <div class="col-md-6">
                    <div class="card">
                        <h5 class="card-header">Register</h5>
                        <div class="card-body">
                        <form method="POST">
                            <?php if($emailControl != "" || $passError != "")
                            {?>
                            <div class="alert alert-danger" role="alert">
                            <?php echo $emailControl;?>
                            <?php echo $passError;?>
                            </div>
                            <?php }?>
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
                            <a href="Login.php">I already have an account.</a>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    </div>
<?php
include '../common/footer.php';
?>