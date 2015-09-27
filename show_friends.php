<?php
include("connect_db.php");
$origin_user_id=$_GET['origin_user_id'];
if (isset($_GET['origin_user_id'])) {
    $origin_user_id=$_GET['origin_user_id'];
}
if (strlen($origin_user_id)==0) die("No owner_user_id parameter set");

?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script src="js/friends.js"></script>
<body onload="getFriendList(<?=$origin_user_id?>);">
   <?php include("views_subs/friends_table.php"); ?>
<input type="button" value="Back" onclick="window.location.href='index.php';">
<span id="editor" style="display:none;">
    id:<input type="text" id="id">
    &nbsp;room name: <input type="text" id="roomname">&nbsp;
    <input type="button" value="Save" onclick="updateRoom($('#id').val(), $('#roomname').val());">
</span>