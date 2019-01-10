<?php
$checkout = '/Golestan/Shop/checkout.php';
$login = "/Golestan/Authentication/Login.php?from='checkout'";
session_start();
include ('../common/General.php');
SessionManage();

if(isset($_POST['checkout'])) {
    if(isset($_SESSION['userID']) && isset($_SESSION['token'])) {
        header('Location: '.$checkout);
    } else {
        header('Location: '.$login);
    }
}

include "../DBConfig.php";
include("ProductController.php");
include("CookieController.php");

$productController = new ProductController();
$cookieController = new CookieController();
$basketController = new BasketController();
$countBasket = false;
$itemCount = 0;
$totalPrice = 0.0;
include '../common/header.php';
if(isset($_POST)){
    if(isset($_POST['deleteItem'])){
        $deleteItem = $_POST['deleteItem'];
        $basketController->deleteItemFromBasket($deleteItem);

    }
    if(isset($_COOKIE['SessionId']))
    {
        $cookieValue = $_COOKIE['SessionId'];
        $result = $basketController->getItemsFromBasket($cookieValue);

        // Check if there is data enable the Checkout button
        if ($result->num_rows === 0) {
            $isThereItem = 'disabled';
        }
        else {
            $isThereItem = "";
        }
    }else {
        $isThereItem = "disabled";
    }
}



?>
    <div class="container">
        <div class="row align-items-center" style="margin-top: 155px;">
            <div class="col-sm-10">
                <form method="post">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Action
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($_COOKIE['SessionId']))
                        {
                            while ($row=mysqli_fetch_array($result))
                            {
                            $id_basket = $row[0];
                            $quantity = $row[2];
                            $price = $row[13];
                            $id_item = $row[9];
                            $id_user = $row[6];
                            $itemTitle = $row[10];
                            $total_price = $price * $quantity;

                            ?>
                                <tr>
                                    <th scope="row" >
                                        <img style="height: 5rem;"
                                             src="/Golestan/assets/images/ProductImages/shop_items<?php echo $id_item; ?>.jpg" >
                                    </th>
                                    <td><?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8');?></td>
                                    <td>Euro <?php echo $total_price;?></td>
                                    <td><?php echo $quantity;?></td>
                                    <td><button type="submit" name="deleteItem" class="btn btn-danger"
                                                value="<?php echo $id_basket;?>">Delete</button></td>
                                </tr>
                        <?php
                                $totalPrice += $total_price;
                                $itemCount += $quantity;
                            }

                        }?>
                    </table>
                </form>
            </div>
            <div class="col-sm-2">
                <form method="post" style="margin-top: 15px;">
                    <p>Subtotal( <?php echo $itemCount ?> item(s)):</p>
                    <p class="font-weight-bold"> EUR <?php echo $totalPrice ?> </p>
                    <button class="btn btn-primary" name="checkout" <?php echo $isThereItem?>>Check out</button>
                </form>
            </div>
        </div>
    </div>
<?php
include '../common/footer.php';
?>