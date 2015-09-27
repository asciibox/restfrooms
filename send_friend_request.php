<?php
include("connect_db.php");
$origin_user_id=$_GET['origin_user_id'];
if (isset($_GET['origin_user_id'])) {
    $origin_user_id=$_GET['origin_user_id'];
}
if (strlen($origin_user_id)==0) die("No origin_user_id parameter set");
?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script src="js/users.js"></script>
<script>
    var global_destination_user_id;
    function invite(destination_user_id) {
        
        global_destination_user_id=destination_user_id;
        $('#reasondiv').show();
         $('#reason').val('');
        $('#reason').focus();
        
        
    }
    
    function invite2(destination_user_id) {
        
        $('#reasondiv').hide();
         $.ajax({
                        url: "rest.php?action=sendfriendrequest&origin_user_id=<?=$origin_user_id?>&destination_user_id="+destination_user_id,
                        type: "POST",
                        data : "reason="+$('#reason').val(),
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                          if (responseText.status == 1) {
                            $('#invitebutton'+destination_user_id).hide();
                        } else {
                            alert(responseText.msg);
                        }
                    });
    }
    
    
    window.onload = function() {
       getUserList("send friend request", "NOT_FRIEND_NOT_INVITED", <?=$origin_user_id?>);
       getUserInfo(<?=$origin_user_id?>,function(responseText) {
            $('#userinfo').html(responseText.email);
       });
    }
</script>
<body>
    All users that are not friends of <span id="userinfo"></span>, and also which are NOT invited already.
    <?php include("views_subs/users_table.php"); ?>
    <span id="reasondiv" style="display:none;"><textarea id="reason" rows="20" cols="70"></textarea><input type="button" value="Send" onclick="invite2(global_destination_user_id);"></span>
<input type="button" value="Back" onclick="window.location.href='admin.php';">