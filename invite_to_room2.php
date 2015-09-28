<?php
  $byuserid="";
  if (isset($_GET['byuserid'])) $byuserid=$_GET['byuserid'];
  if (strlen($byuserid)==0) {
      die("Error: byuserid not set");
  }
  $toroomid="";
  if (isset($_GET['toroomid'])) $toroomid=$_GET['toroomid'];
  if (strlen($toroomid)==0) {
      die("Error: to_roomid not set");
  }
  
include("connect_db.php");
?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script src="js/rooms.js"></script>
<script src="js/friends.js"></script>
<script src="js/users.js"></script>
<script>
   
    function inviteUserToRoom(byuserid, invite_to, toroomid) {
        
        
       $.ajax({
                        url: "rest.php?action=inviteuser&invitation_by="+byuserid+"&invitation_to="+invite_to+"&roomid="+toroomid,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                         
                        
                           $('#invitebutton'+invite_to).parent().html('INVITED');
                      
                        } else {
                            alert(responseText.msg);
                        }
                    });
        
      
    }
</script>
<script>
    window.onload = function() {
        getUserInfo(<?=$byuserid?>, function(responseText) {
            $('#email').html(responseText.email);
            $('#first_name').html(responseText.first_name);
            $('#last_name').html(responseText.last_name);
        });
         getRoomInfo(<?=$toroomid?>, function(responseText) {
            $('#roomname').html(responseText.name);
        });
        getFriendList(<?=$byuserid?>, "INVITE", <?=$byuserid?>, <?=$toroomid?>);
    }
</script>
<body>
    Invitation by:
    <span id="email">&nbsp;</span>&nbsp;(<span id="first_name"></span>&nbsp;<span id="last_name"></span>)<br />
    Invitation to this room:
    <span id="roomname"></span><br />
    <?php include("views_subs/friends_table.php"); ?>
    
<input type="button" value="Back" onclick="document.location.href='showusers.php';">