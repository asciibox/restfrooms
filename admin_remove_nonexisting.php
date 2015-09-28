<?php
include("connect_db.php");
$echoed=false;

$sql="SELECT * FROM rooms";
$query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
while ($row=$query->fetch_assoc()) {
        $sql2="SELECT * FROM users WHERE (id=".$row['owner_user_id'].")";
        $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql2);
        if ($row2=$query->fetch_assoc()) {
        } else {
            $sql3="DELETE FROM rooms WHERE (id=".$row['id'].")";
            echo $sql3."<br />";
            $echoed=true;
            $query3=DBi::$conn->query($sql3) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql3);
        }
}

$sql="SELECT * FROM friends";
$query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
while ($row=$query->fetch_assoc()) {
        $sql2="SELECT * FROM users WHERE (id=".$row['destination_user_id'].")";
        $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql2);
        if ($row2=$query2->fetch_assoc()) {
        } else {
            $sql3="DELETE FROM friends WHERE (id=".$row['id'].")";
            echo $sql3."<br />";
            $echoed=true;
            $query3=DBi::$conn->query($sql3) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql3);
        }
        
        $sql2="SELECT * FROM users WHERE (id=".$row['origin_user_id'].")";
        $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql2);
        if ($row2=$query2->fetch_assoc()) {
        } else {
            $sql3="DELETE FROM friends WHERE (id=".$row['id'].")";
            echo $sql3."<br />";
            $echoed=true;
            $query3=DBi::$conn->query($sql3) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql3);
        }
}

  $sql="SELECT * FROM roommembers";
    
    $roomids=array();
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    while ($row=$query->fetch_assoc()) 
    {
        $roomids[]=$row['origin_room_id'];
    }
    
    $roomlist=array();
    for ($i=0;$i<sizeof($roomids);$i++) {
         $sql="SELECT * FROM rooms WHERE (id=".$roomids[$i].")";
         $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
         if ($row=$query->fetch_assoc()) 
         {
             
         } else {
            $sql2="DELETE FROM roommembers WHERE (origin_room_id=".$roomids[$i].")";
            $query=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql2);
         }
    }

if ($echoed==false) header("Location: index.php");
?>