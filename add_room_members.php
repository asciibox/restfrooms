<?php
include("connect_db.php");


$roomid="";
 if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id'];
 if (strlen($origin_user_id)==0) die("No origin_user_id given");
?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script>
    function join(roomid) {
      
                 $.ajax({
                        url: "rest.php?action=joinroom&roomid="+roomid+"&origin_user_id=<?=$origin_user_id?>",
                        type: "GET",
                        dataType: "json",
                        async: false,
                        processData: false
                    }).done(function(responseText) {
                         
                        if (responseText.status == 1) {
                            $('#joinbutton'+roomid).html('');
                        } else {
                            alert(responseText.msg);
                        }
                    });
      
        
    }
    
      function unjoin(roomid) {
      
                 $.ajax({
                        url: "rest.php?action=unjoinroom&roomid="+roomid+"&origin_user_id=<?=$origin_user_id?>",
                        type: "GET",
                        dataType: "json",
                        async: false,
                        processData: false
                    }).done(function(responseText) {
                         
                        if (responseText.status == 1) {
                            $('#joinbutton'+roomid).html('');
                        } else {
                            alert(responseText.msg);
                        }
                    });
      
        
    }
    
     function addJoinButtons() {
      
        $('.ids').each(function() {
                 roomid=$(this).html();
                 $.ajax({
                        url: "rest.php?action=getismember&roomid="+roomid+"&origin_user_id=<?=$origin_user_id?>",
                        type: "GET",
                        dataType: "json",
                        async: false,
                        processData: false
                    }).done(function(responseText) {
                         
                        if (responseText.status == 1) {
                            if (responseText.joined==0) {
                            $('#joinbutton'+roomid).html("<input type='button' name='add' value='Join the room' onclick=\"join("+roomid+");\">");
                            } else {
                            $('#joinbutton'+roomid).html("<input type='button' name='add' value='UNjoin the room' onclick=\"unjoin("+roomid+");\">");
                            }
                        } else {
                            alert(responseText.msg);
                        }
                    });
                    
                    });
      
        
    }
    </script>
<?php

 
 $sql="SELECT * FROM users WHERE (id=".$_GET['origin_user_id'].")";
 $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
 if ($row=$query->fetch_assoc()) {
     
     echo "Member: ".$row['email']."(".$row['first_name']." ".$row['last_name'].")<br />";
     
 }
 ?>
    <body onload="addJoinButtons();">
        <table><tr><td>ID</td><td>Action</td><td>Room name</td><td>Owner</td></tr>
 <?php
 $sql="SELECT r.*,r.id as room_id, u.*, u.id as user_id FROM rooms r LEFT JOIN users u ON (r.owner_user_id=u.id)";
 $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
 while ($row=$query->fetch_assoc()) {
     
     echo "<tr><td class='ids'>".$row['room_id']."</td><td id='joinbutton".$row['room_id']."'>";
    
     
     echo "<td>".$row['name']."</td><td>".$row['email']."</tr>";
     
 }
 

?>
</table>
    </body>
<input type="button" value="Back" onclick="window.location.href='index.php';">