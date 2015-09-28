<?php
include("connect_db.php");
$owner_user_id="";
if (isset($_GET['owner_user_id'])) {
    $owner_user_id=$_GET['owner_user_id'];
}
if (strlen($owner_user_id)==0) die("No owner_user_id parameter set");

$friends=0;
if (isset($_GET['friends'])) {
    $friends=$_GET['friends'];
}
if (!is_numeric($friends)) die("Please provide either 1 (true) or 0 (false) for the parameter friends");

?>
<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery.min.js"></script>
<script src="js/rooms.js"></script>
<body onload="getRoomList(<?=$owner_user_id?>, <?php if ($friends==1) echo "'FRIENDS'"; ?>);">
   <?php include("views_subs/room_table.php"); ?>
<input type="button" value="Back" onclick="window.location.href='index.php';">
<span id="editor" style="display:none;">
    id:<input type="text" id="id">
    &nbsp;room name: <input type="text" id="roomname">&nbsp;
    <input type="button" value="Save" onclick="updateRoom($('#id').val(), $('#roomname').val());">
</span>