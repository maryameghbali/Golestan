<?php
$countBasket = 0;
$UserName ="";

if(isset($_POST) && isset($_COOKIE['UserBasket'])) {
    $cookie = $_COOKIE['UserBasket'];
    $cardArray = json_decode($cookie, true);
    $countBasket =  count($cardArray);
}


if (isset($_POST['logout']))
{
    include_once('../Shop/CookieController.php');
    $cookieController = new CookieController();
    $cookieController->updateLoggedInToDb($_SESSION['userID'],0);
    session_unset();
    session_destroy();

}
if(isset($_SESSION['UserName'])) {

    $UserName = 'Welcome  '. $_SESSION['UserName'];
}
?>

<head>
    <title>Golestan Online Shop</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Colo Shop Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/Golestan/assets/styles/bootstrap4/bootstrap.min.css">
    <link href="/Golestan/assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/Golestan/assets/plugins/OwlCarousel2-2.2.1/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="/Golestan/assets/plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
    <link rel="stylesheet" type="text/css" href="/Golestan/assets/plugins/OwlCarousel2-2.2.1/animate.css">
    <link rel="stylesheet" type="text/css" href="/Golestan/assets/styles/main_styles.css">
    <link rel="stylesheet" type="text/css" href="/Golestan/assets/styles/responsive.css">
    <link rel="stylesheet" type="text/css" href="/Golestan/assets/styles/single_styles.css">
    <link rel="stylesheet" type="text/css" href="/Golestan/assets/styles/single_responsive.css">
</head>


<body>

<header class="header trans_300">

    <!-- Top Navigation -->

    <div class="top_nav">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="top_nav_left">free shipping on all u.s orders over $50</div>
                </div>
                <div class="col-md-6 text-right">
                    <div class="top_nav_right">
                        <ul class="top_nav_menu">
                            <li class="account">
                                <a href="#">
                                        <?php echo  $UserName ?>

                                </a>

                            </li>
                            <li class="account">
                                <a href="#">
                                    My Account
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <form method="post" type="submit">
                                <ul class="account_selection">
                                    <?php
                                        if (!(isset($_SESSION['userID']) && $_SESSION['userID']!="")){
                                            ?>
                                            <li><a href="/Golestan/Authentication/Login.php"><i class="fa fa-sign-in" aria-hidden="true"></i>Sign In</a></li>
                                            <li><a href="/Golestan/Authentication/Registration.php"><i class="fa fa-user-plus" aria-hidden="true"></i>Register</a></li>
                                            <?php
                                        } else {
                                            ?>
                                            <li><a href="/Golestan/Shop/viewOrders.php"><i aria-hidden="true"></i>View Orders</a></li>
                                            <li><button class="btn btn-info" href="/Golestan/Authentication/Login.php" type="submit" name="logout"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</button></li>
                                            <?php
                                        }
                                    ?>
                                </ul>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->

    <div class="main_nav_container">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-right">
                    <div class="logo_container">
                        <a href="#">Golestan<span>shop</span></a>
                    </div>
                    <nav class="navbar">
                        <ul class="navbar_menu">
                            <li><a href="/Golestan/Index.php">home</a></li>
                            <li><a href="/Golestan/Shop/ProductManage.php">Sell Products</a></li>

                            <li><a href="contact.html">contact</a></li>
                        </ul>
                        <ul class="navbar_user">
                            <li class="checkout">
                                <a href="/Golestan/Shop/basket.php">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    <span id="checkout_items" class="checkout_items"><?php echo $countBasket; ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="hamburger_container">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

</header>

