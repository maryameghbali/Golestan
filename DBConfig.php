<?php
$link = mysqli_connect("localhost:3306","pmauser","Mrt136594$","shop");

class DBConfig {

    public function __construct() {
    }
    public function DBConfig(){
        self::DBConfig();
    }

    static function getLink() {
        return mysqli_connect("localhost:3306","pmauser","Mrt136594$","shop");
    }

}
?>