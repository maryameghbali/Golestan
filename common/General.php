
<?php

function GetMaxId($link,$table)
{

    $sql = "select max(id)  from $table";

    if ($result=mysqli_query($link,$sql)) {
        $row=mysqli_fetch_row($result);
        $MaxId=$row[0];

    } else {
        echo "Error: " . $sql . "<br>" . $link->error;
    }

    return $MaxId;
}
?>