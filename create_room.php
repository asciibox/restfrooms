<?php
include("connect_db.php");
$origin_owner_id="";
if (isset($_GET['origin_owner_id'])) $origin_owner_id=$_GET['origin_owner_id'];
if (strlen($origin_owner_id)==0) {
    die("No origin_owner_id set");
}
?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script src="js/md5.js"></script>
    

<script>
    function submit(roomname) {
     
        
         $.ajax({
                        url: "rest.php?action=addroom&roomname="+roomname+"&owner_user_id=<?=$origin_owner_id?>",
                        type: "GET",
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        if (responseText.status == 1) {
                            window.location.href="rooms.php?owner_user_id=<?=$origin_owner_id?>";
                        } else {
                            alert(responseText.msg);
                        }
                    });
        
        
    }
    
    window.onload = function() {
        $('#roomname').focus();
      $.ajax({
                        url: "rest.php?action=getuser&id=<?=$origin_owner_id?>",
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                        
                        $('#info').html('Create room for '+responseText.email+" ("+responseText.first_name+" "+responseText.last_name+")");
                        
                        
                    });
                }      
    
</script>
<body>
<div id="info"></div>
<table><tr><td>roomname</td></tr>
    <tr><td><input type="text" id="roomname"></td></tr>
</table>
<input type="button" value="Back" onclick="window.location.href='admin.php';"><input type="button" value="Save" onclick="submit($('#roomname').val());">
</body>