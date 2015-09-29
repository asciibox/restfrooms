<?php
include("connect_db.php");
   $sql="SELECT * FROM users";
   
   function randstr($length = 10) {
    $characters = 'abcdefABCDEF';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
   $max=0;
            $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
            while ($row=$query->fetch_assoc()) 
            {
                if (is_numeric($row['email'])) $max=$row['email'];
            }
            for ($i=0;$i<10;$i++) {
                $max++;
                $sql="INSERT INTO users (email, title, first_name, last_name, md5pwd, avatar_url) VALUES ('USER".$max."', 'Herr', 'first_name".randstr(15)."', 'last_name".randstr(15)."', '".MD5($max)."', 'https://www.google.de/logos/doodles/2015/evidence-of-water-found-on-mars-5652760466817024.2-hp.gif');";
                $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
            }
            
            header("Location:admin.php");
?>