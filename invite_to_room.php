<?php
  $invitation_to="";
  if (isset($_GET['invitation_by'])) $invitation_by=$_GET['invitation_by'];
  if (strlen($invitation_by)==0) {
      die("Error: No invitation_by set");
  }
  
include("connect_db.php");
?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script src="js/rooms.js"></script>
<script src="js/users.js"></script>
<script>
   
    
    
    function inviteByUserToRoom(byuserid, toroomid) {
        
      
        
      window.location.href='invite_to_room2.php?byuserid='+byuserid+"&toroomid="+toroomid;
        
    }
</script>
<script>
    window.onload = function() {
        getRoomList(<?=$invitation_by?>, "INVITE", <?=$invitation_by?>);
        getUserInfo(<?=$invitation_by?>, function(responseText) {
            $('#email').html(responseText.email);
            $('#first_name').html(responseText.first_name);
            $('#last_name').html(responseText.last_name);
        });
    }
</script>
<body>
    Invitation by:
    <span id="email">&nbsp;</span>&nbsp;(<span id="first_name"></span>&nbsp;<span id="last_name"></span>)
    <div id="roominfo"></div>
   <?php include("views_subs/room_table.php"); ?>
<input type="button" value="Back" onclick="document.location.href='showusers.php';">