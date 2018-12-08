<?php
include '../common/header.php';
include "../DBConfig.php";
$mes ="";
if(isset($_POST) & !empty($_POST)) {

    if (isset($_POST['login'])) 
    {
        try {
            $statement = $link->prepare('SELECT * FROM shop_user WHERE email LIKE ?');
            $statement->bind_param('s',$_POST['inputEmail']);
            $statement->execute();
            $result = $statement->get_result();

            while ($user = $result->fetch_object()) {
                // Output User info
                $password=$user->password;
                if(password_verify($_POST['inputPassword'], $password)){
                    $mes =  'Welcome';
                }
                else
                {
                    $mes = "Email or password is wrong";

                }
            }
        }
        catch(mysqli_sql_exception $e){
            echo $e->getMessage(), PHP_EOL;
            exit();
        }
        finally
        {
            $link->close();
        }
    }
}
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
                $sql = "SELECT shop_items.id, shop_items.title, shop_items.price, shop_basket.quantity FROM shop_basket INNER JOIN shop_items ON shop_items.id = shop_basket.id_item WHERE shop_basket.id_coockie = 12321";

                $result=mysqli_query($link,$sql);

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