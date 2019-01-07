<?php 
//for Registration part
$inputName = $_POST['inputName'];
$inputAddress = $_POST['inputAddress'];
$inputPhone = $_POST['inputPhone'];
$inputEmail = $_POST['inputEmail'];
$inputPassword = ($_POST['inputPassword']);  
$query = " SELECT inputEmail FROM shop_user WHERE inputEmail = '$inputEmail' ";


//for login part
$inputEmail = $_POST['inputEmail'];
$inputPassword = ($_POST['inputPassword']); 
$query = " SELECT inputEmail FROM shop_user WHERE inputEmail = '$inputEmail' AND password = '$password' ";
?>