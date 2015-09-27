<?php
include("connect_db.php");
?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script src="js/md5.js"></script>
<script>
    function edit(id) {
          $('#editor').show();
        
          $.ajax({
                        url: "rest.php?action=getuser&id="+id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                        
                        $('#id').val(responseText.id);
                        $('#title').val(responseText.title);
                        $('#email').val(responseText.email);
                        $('#first_name').val(responseText.first_name);
                        $('#last_name').val(responseText.last_name);
                        $('#password').val('');
                        $('#avatar_url').val(responseText.avatar_url);
                        $('#sex').val(responseText.sex);
                        
                    });
                    
       
        
    }
    
     function delete_user(id) {
         
        
          $.ajax({
                        url: "rest.php?action=delete_user&id="+id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                        
                       $('#tr'+id).remove();
                        
                    });
                    
       
        
    }
    
    function updateUser(id) {
        
         var id = $('#id').val();
         var title = $('#title').val();
         var email = $('#email').val();
         var first_name = $('#first_name').val();
         var last_name = $('#last_name').val();
         var password = $('#md5pwd').val();
         var md5pwd = CryptoJS.MD5(password);   
         var avatar_url = $('#avatar_url').val();
         var sex = $('#sex').val();
         
         $.ajax({
                        url: "rest.php?action=updateuser&id="+id+"&title="+title+"&email="+email+"&first_name="+first_name+"&last_name="+last_name+"&password="+password+"&md5pwd="+md5pwd+
                             "&avatar_url="+avatar_url+"&sex="+sex,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                            window.location.reload();
                      
                        
                    });
                    
                    

    }
    
    
    function getFriendList() {
        
        $('.id').each(function() {
         id = $(this).html();
         counter=0;
         $.ajax({
                        url: "rest.php?action=getfriendlist&origin_user_id="+$(this).html(),
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                           for (var i = 0; i < responseText.friendlist.length; i++) {
                              
                               if (counter>0) $('#friendlist'+id).html(($('#friendlist'+id)).html()+", ");
                               $('#friendlist'+id).html(($('#friendlist'+id)).html()+responseText.friendlist[i]);
                               counter++;
                           }
                        } else {
                            alert(responseText.msg);
                        }
                    });
                    
        });
        
    }
   
     function getRoomList() {
        
        $('.id').each(function() {
         id = $(this).html();
         counter=0;
         $.ajax({
                        url: "rest.php?action=getjoinedrooms&origin_user_id="+$(this).html(),
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                           for (var i = 0; i < responseText.roomlist.length; i++) {
                              
                               if (counter>0) $('#roomlist'+id).html(($('#roomlist'+id)).html()+", ");
                               $('#roomlist'+id).html(($('#roomlist'+id)).html()+responseText.roomlist[i]);
                               counter++;
                           }
                        } else {
                            alert(responseText.msg);
                        }
                    });
                    
        });
        
    }
</script>
<body onload="getFriendList();getRoomList();">
    <table><tr><td>Action</td><td>id</td><td>email</td><td>title</td><td>first name</td><td>last name</td><td>md5 password</td><td>sex</td><td>friend list</td><td></td><td colspan="2">joined rooms</td></tr>
<?php
            $sql="SELECT * FROM users";
            $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
            while ($row=$query->fetch_assoc()) 
            {
                 ?>
        <tr id="tr<?=$row['id']?>"><td><input type='button' value='Edit' onclick='edit(<?=$row['id']?>);'><input type='button' value='Delete' onclick='delete_user(<?=$row['id']?>);'></td><td class='id'><?=$row['id']?></td><td><?=$row['email']?></td><td><?=$row['title']?></td><td><?=$row['first_name']?></td><td><?=$row['last_name']?></td>
            <td><?=$row['md5pwd']?></td><td><?=$row['sex']?></td><td id="friendlist<?=$row['id']?>">
                <input type="button" value="Show friends" onclick="window.location.href='show_friends.php?origin_user_id=<?=$row['id']?>';"><input type="button" value="Show friend requests" onclick="window.location.href='show_friend_requests.php?origin_user_id=<?=$row['id']?>';"><input type="button" value="Send friend request" onclick="window.location.href='send_friend_request.php?origin_user_id=<?=$row['id']?>';"><input type="button" value="Add friends" onclick="window.location.href='add_friends.php?origin_user_id=<?=$row['id']?>';"><input type="button" value="Remove friends" onclick="window.location.href='remove_friends.php?origin_user_id=<?=$row['id']?>';"></td><td>
                
                <input type="button" value="Create room" onclick="window.location.href='create_room.php?origin_owner_id=<?=$row['id']?>';">
                <input type="button" value="Join (all) rooms" onclick="window.location.href='add_room_members.php?origin_user_id=<?=$row['id']?>';"></td><td id="roomlist<?=$row['id']?>">
                <input type='button' value="User Room list" onclick="window.location.href='rooms.php?owner_user_id=<?=$row['id']?>';"> <input type='button' value="Invite other users to rooms" onclick="window.location.href='invite_to_room.php?invitation_by=<?=$row['id']?>';"><input type='button' value="Show invitations to room" onclick="window.location.href='show_room_invitations.php?user_id=<?=$row['id']?>';"></td></tr></td></tr>
       
                <?php
            }
  
?>
</table>

<input type="button" value="Add a new user" onclick="window.location.href='register.php';"><input type="button" value="Login" onclick="window.location.href='login.php';">

<span id='editor' style='display:none'>
    id:<input type="text" id="id" value="">
    &nbsp;email:<input type="text" id="email" value="">
    &nbsp;title:<input type="text" id="title" value="">
    &nbsp;first_name:<input type="text" id="first_name" value="">
    &nbsp;last_name:<input type="text" id="last_name" value="">
    &nbsp;password:<input type="text" id="md5pwd" value="">
    &nbsp;avatar_url:<input type="text" id="avatar_url" value="">
    &nbsp;sex:<input type="text" id="sex" value="">
    <input type="button" value="Save" onclick="updateUser($('#id').val());">
    
</span>
<input type="button" value="Logout" onclick="localStorage.removeItem('email');localStorage.removeItem('md5pwd');window.location.href='index.php';">
<input type='button' value="Remove nonexisting room owners and friends" onclick="window.location.href='admin_remove_nonexisting.php';"></td></tr>