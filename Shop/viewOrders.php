<?php
$location = "/Golestan/Authentication/Login.php?from='orders'";


session_start();
if(isset($_POST)) {
    if(!(isset($_SESSION['userID']) && isset($_SESSION['token']))) {
        header('Location: '.$location);
    }
}
include "../DBConfig.php";
include '../common/header.php';
include("OrderController.php");


$orderController = new OrderController();
$userId = $_SESSION['userID'];
echo $userId;
?>

<div class="container">
    <div class="row align-items-center" style="margin-top: 155px;">
        <div class="col-sm-12">
            <form method="post">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Order Date</th>
                    </thead>
                    <tbody>
                    <?php

                        $rows = $orderController->getAllCustomerOrder($userId);
                        while ($row=mysqli_fetch_array($rows))
                        {
                            ?>
                            <tr>
                                <th scope="row" >
                                    <img style="height: 5rem;"
                                         src="/Golestan/assets/images/ProductImages/shop_items<?php echo $row[3]; ?>.jpg" >
                                </th>
                                <td><?php echo htmlspecialchars($row[8], ENT_QUOTES, 'UTF-8');?></td>
                                <td>Euro <?php echo $row[2];?></td>

                                <td><?php echo htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8');?></td>
                                <td><?php echo htmlspecialchars($row[5], ENT_QUOTES, 'UTF-8');?></td>
                            </tr>
                            <?php
                        }
                        ?>
                </table>
            </form>
        </div>
    </div>
</div>

<?php
include '../common/footer.php';
?>
