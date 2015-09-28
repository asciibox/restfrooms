<?php
include("connect_db.php");
$user_id="";
if (isset($_GET['user_id'])) {
    $user_id=$_GET['user_id'];
}
if (strlen($user_id)==0) die("No user_id set");

?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script src="js/rooms.js"></script>
<script>
    function confirm(user_id,roomid) {
        
          $.ajax({
                        url: "rest.php?action=joinroom&origin_user_id="+user_id+"&roomid="+roomid,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                        
                           if (responseText.status == 1) {
                         
                             $('#confirmbutton'+roomid).hide();
                        } else {
                            alert(responseText.msg);
                        }
                        
                      
                        
                    });
        
    }
</script>
<body onload="getRoomInvitationList(<?=$user_id?>);">
   <?php include("views_subs/room_invitations_table.php"); ?>
<input type="button" value="Back" onclick="window.location.href='index.php';">
<span id="editor" style="display:none;">
    id:<input type="text" id="id">
    &nbsp;room name: <input type="text" id="roomname">&nbsp;
    <input type="button" value="Save" onclick="updateRoom($('#id').val(), $('#roomname').val());">
</span>