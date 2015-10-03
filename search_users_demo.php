<?php
include("connect_db.php");

$origin_user_id="";
if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id'];
if (strlen($origin_user_id)>0) {
	$additional_parameters="&get_invite_status_by_origin_user_id=".$origin_user_id;
}
?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script>
    var itemsPerPage = 8;
	var destination_user_id = -1;
    function up() {
        var current_page=$('#current_page').html();
        if (current_page>1) current_page--;
        $('#current_page').html(current_page);
        search($('#username').val(), itemsPerPage);
        $('#username').focus();
    }
	function inviteUser(id) {
		$('#reason').val('');
		destination_user_id=id;
		$('#reasondiv').show();
	}
	function abort() {
		$('#reason').val('');
		$('#reasondiv').hide();
	}

	function submitInvitation() {
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
                            $('#invitebutton'+destination_user_id).parent().html('OK - invited');
                        } else {
                            alert(responseText.msg);
                        }
                    });
    }



    function search(name, itemsPerPage) {
        
        $.ajax({
                        url: "rest.php?action=searchuser&name="+name+"&page="+$('#current_page').html()+"&items_per_page="+itemsPerPage+"<?=$additional_parameters?>",
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                        $('#usertable').html("<tr><td>email</td><td>title</td><td>first_name</td><td>last_name</td><td>avatar_url</td><td>Action</td></tr>");
                        var users = responseText.users;
                        if (typeof(users)!="undefined") {
                        for (var i = 0; i < users.length; i++) {
						  if (users[i].already_invited==1)
						  {
							var action="Already invited";
						  } else {
							  var action="<input id='invitebutton"+users[i].id+"' type='button' value='inviteUser("+users[i].id+");' onclick='inviteUser("+users[i].id+");'>";						  }

                          $('#usertable').append("<tr><td>"+users[i].email+"</td><td>"+users[i].title+"</td><td>"+users[i].first_name+"</td><td>"+users[i].last_name+"</td><td>"+users[i].avatar_url+"</td><td>"+action+"</td></tr>");   
                        }
                        }
        
                    });
                    
    }
                    
    function down() {
        var current_page=$('#current_page').html();
        current_page++;
        $('#current_page').html(current_page);
        search($('#username').val(), itemsPerPage);
        $('#username').focus();
    }
    
</script>
<body onload="$('#username').focus();$('#username').val('');$('#info').html('Items per page: '+itemsPerPage);">
    <div id="info"></div>
	
    <div>Current page:<span id="current_page">1</div>
    <input type="button" value="PAGE UP" onclick="up();">
<input type="text" name="username" id="username" value="" onkeyup="$('#current_page').html('1');search(this.value, itemsPerPage);">
<input type="button" value="PAGE DOWN" onclick="down();">
<table id="usertable">
</table>
<span id="reasondiv" style="display:none;"><br />Reason for invitation:<br /><textarea rows="20" id='reason' cols="50"></textarea><input type='button' id='submitbutton' value='submit' onclick='submitInvitation();'><input type='button' value='Abort' onclick='abort();'></span>
</body>