<?php
$checkout = '/Golestan/Shop/checkout.php';
$login = "/Golestan/Authentication/Login.php?from='checkout'";
session_start();

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
$itemCount = 0;
$totalPrice = 0.0;

if(isset($_POST)){
    if(isset($_POST['deleteItem'])){
        $deleteItem = $_POST['deleteItem'];
        $msg= $cookieController->deleteCookie($deleteItem);
        header("Refresh:0");
    }
    if(isset($_COOKIE['UserBasket']))
    {
        $cookie = $_COOKIE['UserBasket'];
        $cardArray = json_decode($cookie, true);
    }
    if(isset($_COOKIE['UserBasket'])) {
        $cookie = $_COOKIE['UserBasket'];
        $cardArray = json_decode($cookie, true);
        $countBasket =  count($cardArray);
        if ($countBasket <= 0) {
            $isThereItem = 'disabled';
        }
        else {
            $isThereItem = "";
        }
    }else {
        $isThereItem = "disabled";
    }
}

include '../common/header.php';

?>
    <div class="container">
        <div class="row align-items-center" style="margin-top: 150px;">
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
                        <?php if(isset($_COOKIE['UserBasket']))
                        {
                            foreach($cardArray as $products){
                                foreach($products as $productId => $quantity)
                                {
                                    $rows = $productController->getProdcutById($productId);
                                    while ($row=mysqli_fetch_array($rows))
                                    {
                                    $totalPrice += $row[4];

                                    ?>
                                        <tr>
                                            <th scope="row" >
                                                <img style="height: 5rem;"
                                                     src="/Golestan/assets/images/ProductImages/shop_items<?php echo $row[0]; ?>.jpg" >
                                            </th>
                                            <td><?php echo $row[1];?></td>
                                            <td>Euro <?php echo $row[4];?></td>
                                            <td><input type="number"
                                                       onchange="updateQuantity(<?php echo $productId?>, value)"
                                                       type="number"
                                                       max="<?php echo $row[3]?>"
                                                       min="1"
                                                       value="<?php echo $quantity;?>"
                                                       style="width: 50px;"
                                                >
                                            </td>
                                            <td><button type="submit" name="deleteItem" class="btn btn-danger"
                                                        value="<?php echo $row[0];?>">Delete</button></td>
                                        </tr>
                                <?php
                                        $itemCount++;
                                    }
                                }
                            }
                        }?>
                    </table>
                </form>
            </div>
            <div class="col-sm-2">
                <form method="post">
                    <p>Subtotal( <?php echo $itemCount ?> item(s)):</p>
                    <p class="font-weight-bold"> EUR <?php echo $totalPrice ?> </p>
                    <button class="btn btn-primary" name="checkout" <?php echo $isThereItem?>>Check out</button>
                </form>
            </div>
        </div>
    </div>
<script>
    function updateQuantity(productId, quantity) {
        $.ajax({url: 'basket.php',
                data: {productId: productId, quantity:quantity},
                type: 'post',
                success: function (output) {
                    console.warn(output);
                }
        });
    }
</script>
<?php
include '../common/footer.php';
?>