<?php
$checkout = '/Golestan/Shop/checkout.php';
$login = '/Golestan/Authentication/Login.php';
session_start();

if(isset($_POST['checkout'])) {
    if(isset($_SESSION['userID']) && isset($_SESSION['token'])) {
        header('Location: '.$checkout);
    } else {
        header('Location: '.$login);
    }
}
include '../common/header.php';
include "../DBConfig.php";
include("BasketController.php");
include("productController.php");
$controller = new BasketController();
$productController = new ProductController();
$itemCount = 0;
$totalPrice = 0.0;
$cookie = $_COOKIE['UserBasket'];
$cardArray = json_decode($cookie, true);
?>
    <div class="container">
        <div class="row align-items-center" style="margin-top: 150px;">
            <div class="col-sm-10">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">product</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($_COOKIE['UserBasket']))
                    {
                        foreach($cardArray as $products){ 
                            foreach($products as $key => $value) 
                            {
                                $rows = $productController->getProdcutById($key);
                                while ($row=mysqli_fetch_array($rows))
                                {
                                $totalPrice += $row[4];
                                $itemCount++;
                                ?>
                                    <tr>
                                        <th scope="row" ><img style="height: 5rem;"
                                                            src="/Golestan/assets/images/ProductImages/shop_items<?php echo $row[0]; ?>.jpg" >
                                        </th>
                                        <td><?php echo $row[1];?></td>
                                        <td><?php echo $row[3];?></td>
                                        <td>Euro <?php echo $row[4];?></td>
                                        <td><button class="btn btn-danger" value="<?php echo $row[0];?>">Delete</button></td>
                                    </tr>
                            <?php 
                                } 
                            }
                        }
                    }?>
                </table>
            </div>
            <div class="col-sm-2">
                <form method="post">
                    <p>Subtotal( <?php echo $itemCount ?> item(s)):</p>
                    <p class="font-weight-bold"> EUR <?php echo $totalPrice ?> </p>
                    <button class="btn btn-primary" name="checkout">Check out</button>
                </form>
            </div>
        </div>
    </div>
<?php
include '../common/footer.php';
?>