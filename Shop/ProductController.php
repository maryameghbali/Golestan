<?php

class ProductController
{
    public function __construct()
    {
    }

    public function ProductController(){
        self::ProductController();
    }

    function addNewProduct($name, $des, $stock, $price) {
        $sql = "insert into shop_items (title,description,stock,price) values ('".$name."','".$des."',".$stock.",".$price.")";
        $link = DBConfig::getLink();

        if ($link->query($sql) === TRUE) {

            $fn=$_FILES['image']['tmp_name'];
            $MaxId = GetMaxId($link,"shop_items");
            $path = "../assets/images/ProductImages/shop_items".$MaxId.".jpg";
            move_uploaded_file($fn,$path);
            return "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }

        $link->close();
    }

    function getAllProduction(){
        $sql = "select *  from shop_items";
        $link = DBConfig::getLink();
        $result=mysqli_query($link,$sql);
        $link->close();
        return $result;
    }
}