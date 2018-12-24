<?php
include '../common/header.php';
include "../DBConfig.php";
include "UserController.php";

if(isset($_POST) & !empty($_POST)) {

    if (isset($_POST['register'])) 
    {
        $controller = new UserController();
        $controller->addNewUser($_POST['inputName'], $_POST['inputAddress'],
            $_POST['inputPhone'], $_POST['inputEmail'], $_POST['inputPassword']);
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