<?php
include("connect_db.php");
?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script>
    var itemsPerPage = 8;
    function up() {
        var current_page=$('#current_page').html();
        if (current_page>1) current_page--;
        $('#current_page').html(current_page);
        search($('#username').val(), itemsPerPage);
        $('#username').focus();
    }
    function search(name, itemsPerPage) {
        
        $.ajax({
                        url: "rest.php?action=searchuser&name="+name+"&page="+$('#current_page').html()+"&items_per_page="+itemsPerPage,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                        $('#usertable').html("<tr><td>email</td><td>title</td><td>first_name</td><td>last_name</td><td>avatar_url</td></tr>");
                        var users = responseText.users;
                        if (typeof(users)!="undefined") {
                        for (var i = 0; i < users.length; i++) {
                          $('#usertable').append("<tr><td>"+users[i].email+"</td><td>"+users[i].title+"</td><td>"+users[i].first_name+"</td><td>"+users[i].last_name+"</td><td>"+users[i].avatar_url+"</td></tr>");   
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
<?php
?>
</body>