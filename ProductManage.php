<?php
include "header.php";
include "DBConfig.php";

if(isset($_POST) & !empty($_POST)) {

    if (isset($_POST['Add'])) {

        $sql = "insert into shop_items (title,description,stock,price) 
            values ('".$_POST['Name']."','".$_POST['Description']."',".$_POST['Stock'].",".$_POST['Price'].")";

        if ($link->query($sql) === TRUE) {
           echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }

        $link->close();
    }
}


?>
<div class="container">
    <form method="post">

        <div class="form-group">
            <label for="exampleFormControlInput1">Name</label>
            <input class="form-control" type="text" placeholder="Name"  name="Name">
        </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Description</label>
            <textarea class="form-control" name="Description" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Stock</label>
            <input class="form-control" type="text" placeholder="Stock" name="Stock">
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Price</label>
            <input class="form-control" type="text" placeholder="Price" name="Price">
        </div>
        <button type="submit" class="btn btn-primary"  name="Add" id="Add">Submit</button>
    </form>


</div>

<?php
include "footer.php";
?>