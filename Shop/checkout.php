<?php
$location = '/Golestan/Authentication/Login.php';

include "../DBConfig.php";
include '../Authentication/UserController.php';
session_start();
if(isset($_POST)) {
    if(!isset($_SESSION['userID']) && !isset($_SESSION['token'])) {
        header('Location: '.$location);
    } else {
        include '../common/header.php';
        $userController = new UserController();
        $result = $userController->getUserById($_SESSION['userID']);
        $address = $result->address;
    }

    if(isset($_POST['updateAddress'])){
        $newAddress= $_POST['newAddress'];
        $userId = $_SESSION['userID'];
        $userController->updateUserAddress($userId, $newAddress);
        $address = $newAddress;
    }
}



?>


<div class="container fill_height">
    <div class="row align-items-center fill_height">
        <div class="card col-sm-4" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Receiver address</h5>
                <p class="card-text"><?php print_r($address)?></p>
                <a href="#" class="card-link"  data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Change the Address</a>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Address</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">Address:</label>
                                    <textarea class="form-control" id="message-text" name="newAddress"></textarea>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="updateAddress">Update Address</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card col-sm-4" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Payment Method</h5>
                <div class="container">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
                        <label class="form-check-label" for="exampleRadios1">
                            Credit Cart
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                        <label class="form-check-label" for="exampleRadios2">
                            Paypal
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="option3">
                        <label class="form-check-label" for="exampleRadios3">
                            Giro account
                        </label>
                    </div>
                </div>

                <!-- Button trigger modal -->
                <a href="#" class="card-link" data-toggle="modal" data-target="#exampleModalCenter">Select</a>


                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">Paypal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <p style="color: green;text-align: center"></p>
                                        <div class="form-group">
                                            <label for="inputEmail">Email address</label>
                                            <input type="email" class="form-control" name="inputEmail" aria-describedby="emailHelp" placeholder="Enter email">
                                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword">Password</label>
                                            <input type="password" class="form-control" name="inputPassword" placeholder="Password">
                                        </div>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit"  class="btn btn-primary">Login to Paypal</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include '../common/footer.php';
?>
