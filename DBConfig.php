<?php
$link = mysqli_connect("localhost:3306","root","","shop");

class DBConfig {

    public function __construct() {
    }
    public function DBConfig(){
        self::DBConfig();
    }

    static function getLink() {
        $link = mysqli_connect("localhost:3306","root","","shop");
        if ($link->connect_error) {
            die("Connection failed: " . $link->connect_error);
        }
        return  $link;
    }

}
?>