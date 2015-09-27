<?php
include("connect_db.php");
?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script src="js/md5.js"></script>
    

<script>
    function submit(email,title,first_name,last_name,md5pwd,md5pwd2,sex) {
        if (md5pwd!=md5pwd2) {
            $('#info').html('Passwords do not match');
            return;
        } else {
            $('#info').html('');
        }
        
        md5pwd = CryptoJS.MD5(md5pwd);    
        
         $.ajax({
                        url: "rest.php?action=adduser&email="+email+"&title="+title+"&first_name="+first_name+"&last_name="+last_name+"&md5pwd="+md5pwd+"&sex="+sex,
                        type: "GET",
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        if (responseText.status == 1) {
                            window.location.href="index.php";
                        } else {
                            alert(responseText.msg);
                        }
                    });
        
        
    }
    
</script>
<div id="info"></div>
<table><tr><td>email</td><td>title</td><td>first name</td><td>last name</td><td>password</td><td>password repetition</td><td>sex</td></tr>
    <tr><td><input type="text" id="email"></td><td><input type="text" id="title"></td><td><input type="text" id="first_name"></td><td><input type="text" id="last_name"></td><td><input type="text" id="md5pwd"></td><td><input type="text" id="md5pwd2"></td><td><select id="sex"><option value="0">female</option><option value="1">male</option></select></td></tr>
</table>
<input type="button" value="Back" onclick="window.location.href='index.php';"><input type="button" value="save" onclick="submit($('#email').val(), $('#title').val(), $('#first_name').val(), $('#last_name').val(), $('#md5pwd').val(), $('#md5pwd2').val(), $('#sex').val());">