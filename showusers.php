<?php
header("Location: admin.php");
exit;
include("connect_db.php");


?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script src="js/rooms.js"></script>
<script>
    function invite(id) {
        
         window.location.href='invite_to_room.php?invitation_to='+id;
        
    }
    
    function getFriendlistArray(origin_user_id) {
    
    
     
         $.ajax({
                        url: "rest.php?action=getfriendlistarray&origin_user_id="+origin_user_id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                           if (responseText.friendlistarray.length==0) $('#friendlistarray_info').html('You have no friends');
                           for (var i = 0; i < responseText.friendlistarray.length; i++) {
                              
                              var friendlist = responseText.friendlistarray[i];
                              $('friendlistarray_table').append("<tr><td>"+friendlist.id+"</td><td>"+friendlist.email+"</td><td>"+friendlist.title+"</td><td>"+friendlist.first_name+"</td><td>"+friendlist.last_name+"</td><td>"+friendlist.avatar_url+"</td><td>"+riendlist.sex+"</td></tr>");
                           }
                        } else {
                            alert(responseText.msg);
                        }
                    });
                    
     
    
    }
    
    window.onload = function() {
       getUserList();
       getRoomList();
       getFriendlistArray(localStorage.getItem("userid"));
    }
</script>
<body>
    All users
    <?php include("views_subs/users_table.php"); ?>
    Your rooms
    <?php include("views_subs/room_table.php"); ?>
    <input type="button" value="Create a new room" onclick="create_new_room.php">
    Your friends
    <?php include("views_subs/friendlistarray_table.php"); ?><div id="friendlistarray_info"></div><input type="button" value="Logout" onclick="localStorage.removeItem('email');localStorage.removeItem('md5pwd');window.location.href='index.php';">