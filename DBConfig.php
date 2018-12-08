<?php
$link = mysqli_connect("localhost:3306","root","","shop");

class DBConfig {

    public function __construct() {
    }
    public function DBConfig(){
        self::DBConfig();
    }

    static function getLink() {
        return mysqli_connect("localhost:3306","root","","shop");
    }

}
?>