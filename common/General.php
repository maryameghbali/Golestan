
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
function SessionManage()
{

    //Expire the session if user is inactive for 30
//minutes or more.
    $expireAfter = 10;

//Check to see if our "last action" session
//variable has been set.
    if(isset($_SESSION['last_action'])){

        //Figure out how many seconds have passed
        //since the user was last active.
        $secondsInactive = time() - $_SESSION['last_action'];

        //Convert our minutes into seconds.
        $expireAfterSeconds = $expireAfter * 60;

        //Check to see if they have been inactive for too long.
        if($secondsInactive >= $expireAfterSeconds){
            //User has been inactive for too long.
            //Kill their session.
            session_unset();
            session_destroy();
            session_start();
        }

    }

//Assign the current timestamp as the user's
//latest activity
    $_SESSION['last_action'] = time();

}
?>