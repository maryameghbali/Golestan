<?php
$loginPage = "/Golestan/Authentication/Login.php?from='addProduct'";


session_start();
$mes ="";
if(isset($_POST)) {
    if(isset($_SESSION['userID']) && isset($_SESSION['token'])) {
        $token = $_SESSION['token'];
    } else {
        header('Location: '.$loginPage);
    }
}
$userId = $_SESSION['userID'];

include  "../common/header.php";
include  "../common/General.php";
include "../DBConfig.php";
include "ProductController.php";

$controller = new ProductController();

if (isset($_POST['Add'])) {
    if (hash_equals($token, $_POST['token'])) {
        $controller = new ProductController();
        $mes = $controller->addNewProduct($_POST['Name'],$_POST['Description'],$_POST['Stock'],$_POST['Price'], $_SESSION['userID']);
    } else {
        $mes = "Somethings goes wrong!";
    }
}

if(isset($_POST['deleteItem'])){
    $item_id = $_POST['deleteItem'];
    $userId = $_SESSION['userID'];
    $controller->deleteItem($userId,$item_id);
}

?>
        <div class="container">
            <div class="row" style="margin-top: 110px;">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Add new Product
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden"  name="token" value="<?php echo $token ?>">
                                <p style="color: green;text-align: center"> <?php echo $mes?></p>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Name</label>
                                    <input class="form-control" type="text" placeholder="Name"  name="Name">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Description</label>
                                    <textarea class="form-control" name="Description" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Stock</label>
                                    <input class="form-control" type="text" placeholder="Stock" name="Stock">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Price</label>
                                    <input class="form-control" type="text" placeholder="Price" name="Price">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">image</label>
                                    <input type="file" id="image" class="form-control" name="image">
                                </div>
                                <button type="submit" class="btn btn-primary"  name="Add" id="Add">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">product</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Price</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $result = $controller->getAllUserProduction($userId);
                        while ($row=mysqli_fetch_array($result))
                        {
                            ?>
                            <form method="post">
                                <tr>
                                    <th scope="row" ><img style="height: 5rem;"
                                                          src="/Golestan/assets/images/ProductImages/shop_items<?php echo $row[0]; ?>.jpg" >
                                    </th>
                                    <td><?php echo $row[1];?></td>
                                    <td><?php echo $row[3];?></td>
                                    <td>Euro <?php echo $row[4];?></td>
                                    <td><button type="submit" class="btn btn-danger" name="deleteItem" value="<?php echo $row[0];?>">Delete</button></td>
                                </tr>
                            </form>
                            <?php
                        }

                        ?>
                    </table>
                </div>
            </div>
        </div>


<?php
include '../common/footer.php';
?>