<?php
require("connect_db_settings.php");

/*define('DB_SERVER', getenv('OPENSHIFT_MYSQL_DB_HOST'));
define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
define('DB_PASSWORD',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));*/

class DBi {
    public static $conn;
    
    public static function mysqli_result($res, $row=0,$col=0){ 
    $numrows = mysqli_num_rows($res);
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}
    
    
}
DBi::$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
?>