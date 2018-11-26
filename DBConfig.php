<?php
$link = new mysqli("localhost:3306","pmauser","Mrt136594$","shop");

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}