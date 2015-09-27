
   
    <?php
include("connect_db.php");

function isFriend($origin_user_id, $destination_user_id) {
    
    $sql="SELECT * FROM friends WHERE (origin_user_id=".$origin_user_id.") AND (destination_user_id=".$destination_user_id.")";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    if ($row=$query->fetch_assoc()) 
    {
        return true;
    } else {
        return false;
    }
    
}


    $origin_user_id="";
    if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id']; 
    if (strlen($origin_user_id)==0) die("origin_user_id not set");
    
    $sql="SELECT * FROM users WHERE (id=".$origin_user_id.")";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    if ($row=$query->fetch_assoc()) 
         {
            $email=$row['email'];
            if (strlen($email)==0) $email="[EMAIL IS EMPTY]";
             echo "Add friends for <span style='color:red'>".$email."</span> (".$row['first_name']." ".$row['last_name'].")";
         } else {
             echo "Userid ".$origin_user_id." not found!";
         }
?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script> function addFriends() {
        var total = $("input:checkbox[name=add]:checked").length;
        if (total==0) alert("Please check some friends");
        
        $("input:checkbox[name=add]:checked").each(function()
        {
                 addid = $(this).attr('id');
                 $.ajax({
                        url: "rest.php?action=addfriend&destination_user_id="+addid+"&origin_user_id=<?=$origin_user_id?>",
                        type: "GET",
                        dataType: "json",
                        async: false,
                        processData: false
                    }).done(function(responseText) {
                      
                        if (responseText.status == 1) {
                          
                            $('#id'+addid).html('added');
                        } else {
                            alert(responseText.msg);
                        }
                    });
        });
      
    }
    
    
    function createFriendlistToAdd() {
    
        $('#friendlist').append('<tr><td>ADD</td><td>id</td><td>email</td><td>title</td><td>first name</td><td>last name</td><td>md5 password</td><td>sex</td></tr>');
    
        
         $.ajax({
                        url: "rest.php?action=getfriendlistarray&mode=inverse&origin_user_id=<?=$origin_user_id?>",
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                           for (var i = 0; i < responseText.friendlistarray.length; i++) {
                              
                               var frienditem = responseText.friendlistarray[i];
                               $('#friendlist').append("<tr><td id='id"+frienditem.id+"'><input type='checkbox' id='"+frienditem.id+"' name='add' value='true'></td><td>"+frienditem.id+"</td><td>"+frienditem.email+"</td><td>"+frienditem.title+"</td><td>"+frienditem.first_name+"</td><td>"+frienditem.last_name+"</td><td>"+frienditem.avatar_url+"</td><td>"+frienditem.sex+"</td></tr>");
                             
                           }
                        } else {
                            alert(responseText.msg);
                        }
                    });
                    
    
    
    }
    
    window.onload = function() {
            createFriendlistToAdd();
    }
    
    
</script>
<body>
        <table id="friendlist">
        </table>
    
<input type="Submit" onclick="addFriends();"><input type="button" value="Back" onclick="window.location.href='index.php';">