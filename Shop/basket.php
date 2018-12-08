<?php
include '../common/header.php';
include("BasketController.php");
$controller = new BasketController();

?>
    <div class="container fill_height" style="padding-top: 100px">
        <div class="row align-items-center fill_height">
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
                <?php
                $result = $controller->getAllBasketItems("12321");
                while ($row=mysqli_fetch_array($result))
                { ?>
                <tr>
                    <th scope="row" ><img style="height: 5rem;" src="/Golestan/assets/images/ProductImages/shop_items<?php echo $row[0]; ?>.jpg" ></th>
                    <td><?php echo $row[1];?></td>
                    <td><?php echo $row[3];?></td>
                    <td>Euro <?php echo $row[2];?></td>
                    <td><button class="btn btn-danger">Delete</button></td>
                </tr>

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