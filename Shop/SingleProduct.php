<?php
include  "../common/header.php";

$ShowId=$_GET['Id'];
$sql = "select *  from shop_items where id='$ShowId'";

if ($result=mysqli_query($link,$sql)) {
    $row=mysqli_fetch_row($result);
}
?>

<div class="container single_product_container">
    <div class="row">
        <div class="col">

            <!-- Breadcrumbs -->

            <div class="breadcrumbs d-flex flex-row align-items-center">
               
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="single_product_pics">
                <div class="row">

                    <div class="col-lg-12 image_col order-lg-2 order-1">
                        <div class="single_product_image">
                            <div class="single_product_image_background" style="background-image:url('../assets/images/ProductImages/shop_items<?php echo $row[0]; ?>.jpg')"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="product_details">
                <div class="product_details_title">
                    <h2><?php echo $row[1]; ?></h2>
                    <p><?php echo substr($row[2],0,200); ?>...</p>
                </div>
                <div class="free_delivery d-flex flex-row align-items-center justify-content-center">
                    <span class="ti-truck"></span><span>free delivery</span>
                </div>

                <div class="product_price">EUR <?php echo $row[4]; ?></div>
                <ul class="star_rating">
                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                    <li><i class="fa fa-star-o" aria-hidden="true"></i></li>
                </ul>
                <div class="product_color">
                    <span>Select Color:</span>
                    <ul>
                        <li style="background: #e54e5d"></li>
                        <li style="background: #252525"></li>
                        <li style="background: #60b3f3"></li>
                    </ul>
                </div>
                <div class="quantity d-flex flex-column flex-sm-row align-items-sm-center">
                    <span>Quantity:</span>
                    <div class="quantity_selector">
                        <span class="minus"><i class="fa fa-minus" aria-hidden="true"></i></span>
                        <span id="quantity_value">1</span>
                        <span class="plus"><i class="fa fa-plus" aria-hidden="true"></i></span>
                    </div>
                    <div class="red_button add_to_cart_button"><a href="#">add to cart</a></div>
                    <div class="product_favorite d-flex flex-column align-items-center justify-content-center"></div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Tabs -->

<div class="tabs_section_container">

    <div class="container">

        <div class="row">
            <div class="col">

                <!-- Tab Description -->

                <div id="tab_1" class="tab_container active">
                    <div class="row">
                        <div class="col-lg-12 desc_col">
                            <div class="tab_title">
                                <h4>Description</h4>
                            </div>
                            <div class="tab_text_block">
                                <h2><?php echo $row[1]; ?></h2>
                                <p><?php echo $row[2]; ?></p>
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