<?php
include "../dbConfig.php";

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$sql = "insert into shop_items (title,description,stock,price) values ('test1','testdesc',5,4500)";

if ($link->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}

$link->close();
?>