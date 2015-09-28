<?php
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


function getRoomMembers($id) {
    
    $roommembers=array();
    $sql="SELECT * FROM roommembers m LEFT JOIN users u ON (m.member_id=u.id) WHERE (origin_room_id='".$id."')";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    while ($row=$query->fetch_assoc()) {
        $roommembers[]=$row['email'];
    }
    return $roommembers;   
}
function getFriendIDS($origin_user_id) {
    
    $ids=array();
    $sql="SELECT * FROM friends WHERE ( (origin_user_id=".$origin_user_id.") OR (destination_user_id=".$origin_user_id.") )";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    while ($row=$query->fetch_assoc()) {
        
        if ($row['destination_user_id']==$origin_user_id) {
            $ids[]=$row['origin_user_id'];
        } else {
            $ids[]=$row['destination_user_id'];
        }
    }
    return $ids;
    
}


function getNOTFriendIDS($origin_user_id) {
    
    $ids=array();
   
    $sql="SELECT * FROM users WHERE (id!=".$origin_user_id.")";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    while ($row=$query->fetch_assoc()) {
            $destination_user_id=$row['id'];
            $sql2="SELECT * FROM friends WHERE ( (origin_user_id=".$origin_user_id.") AND (destination_user_id=".$destination_user_id.") ) OR ( (origin_user_id=".$destination_user_id.") AND (destination_user_id=".$origin_user_id.") ) ";
            $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
            if ($row2=$query2->fetch_assoc()) {
              // do nothing
            } else {
                $ids[]=$row['id'];
            }
    }
    
   
    return $ids;
    
}

include("connect_db.php");
$action="";
if (isset($_GET['action'])) $action=$_GET['action'];

if (strcmp($action, "adduser")==0) {
    
    $email="";
    $title="";
    $first_name="";
    $last_name="";
    $md5pwd="";
    $sex="";
    
    if (isset($_GET['email'])) $email=$_GET['email'];
    if (isset($_GET['title'])) $title=$_GET['title'];
    if (isset($_GET['first_name'])) $first_name=$_GET['first_name'];
    if (isset($_GET['last_name'])) $last_name=$_GET['last_name'];
    if (isset($_GET['md5pwd'])) $md5pwd=$_GET['md5pwd'];
    if (isset($_GET['sex'])) $sex=$_GET['sex'];
        
    if (strlen($email)==0) die(json_encode(array("status" => 0, "msg" => "email not set")));
    if (strlen($title)==0) die(json_encode(array("status" => 0, "msg" => "title not set")));
    if (strlen($first_name)==0) die(json_encode(array("status" => 0, "msg" => "first_name not set")));
    if (strlen($last_name)==0) die(json_encode(array("status" => 0, "msg" => "first_name not set")));
    if (strlen($md5pwd)==0) die(json_encode(array("status" => 0, "msg" => "first_name not set")));
    if (strlen($sex)==0) die(json_encode(array("status" => 0, "msg" => "first_name not set")));
    
    $sql="SELECT * FROM users WHERE (email='".$email."')";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    if ($row=$query->fetch_assoc()) {
       
     echo json_encode(array("status" => 0, "msg" => "email already exists".$sql));
    } else {
        $sql = "INSERT INTO users (email, title, first_name, last_name, md5pwd, sex) VALUES ('".$email."', '".$title."', '".$first_name."', '".$last_name."', '".$md5pwd."', ".$sex.")";
        $query = DBi::$conn->query($sql) or die(json_encode(array("status" => 0, "error" => DBi::$conn->error . " " . __FILE__ . " line " . __LINE__)));
        echo json_encode(array("status" => 1, "msg" => "success"));
    }
    
} else
if (strcmp($action, "addfriend")==0) {
    $origin_user_id="";
    if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id']; 
    if (strlen($origin_user_id)==0) die(json_encode(array("status" => 0, "msg" => "origin_user_id not set")));
    
    $destination_user_id="";
    if (isset($_GET['destination_user_id'])) $destination_user_id=$_GET['destination_user_id']; 
    if (strlen($destination_user_id)==0) die(json_encode(array("status" => 0, "msg" => "destination_user_id not set")));
    
    $sql="SELECT * FROM friends WHERE (destination_user_id='".$destination_user_id."') AND (origin_user_id='".$origin_user_id."')";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    if ($row=$query->fetch_assoc()) 
    {
        echo json_encode(array("status" => 0, "msg" => "Users are already friends"));
    } else {
        
        $sql="INSERT INTO friends (origin_user_id, destination_user_id) VALUES (".$origin_user_id.", ".$destination_user_id.")";
        $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
        echo json_encode(array("status" => 1, "msg" => "Users are now friends"));
    }
    
    
} else
if (strcmp($action, "removefriend")==0) {
    $origin_user_id="";
    if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id']; 
    if (strlen($origin_user_id)==0) die(json_encode(array("status" => 0, "msg" => "origin_user_id not set")));
    
    $destination_user_id="";
    if (isset($_GET['destination_user_id'])) $destination_user_id=$_GET['destination_user_id']; 
    if (strlen($destination_user_id)==0) die(json_encode(array("status" => 0, "msg" => "destination_user_id not set")));
    
    $sql="DELETE FROM friends WHERE ( (destination_user_id='".$destination_user_id."') AND (origin_user_id='".$origin_user_id."') ) OR ( (destination_user_id='".$origin_user_id."') AND (origin_user_id='".$destination_user_id."') ) ";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
   
    echo json_encode(array("status" => 1, "msg" => "OK"));
   
    
} else
if (strcmp($action, "login")==0) {
    $email="";
    $password="";
    if (isset($_GET['email'])) $email=$_GET['email'];
    if (isset($_GET['password'])) $password=$_GET['password'];
    if (strlen($email)==0) die(json_encode(array("status" => 0, "msg" => "email not set")));
    if (strlen($password)==0) die(json_encode(array("status" => 0, "msg" => "no password given")));
    
    $sql="SELECT * FROM users WHERE (email='".$email."') AND (md5pwd='".$password."')";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    if ($row=$query->fetch_assoc()) 
    {
        echo json_encode(array("status" => 1, "msg" => "logging in", "userid" => $row['id']));
    } else {
        echo json_encode(array("status" => 0, "msg" => "email not found or wrong password"));
    }
}
else
if (strcmp($action, "getfriendids")==0) {
    $origin_user_id="";
    if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id']; 
    if (strlen($origin_user_id)==0) die(json_encode(array("status" => 0, "msg" => "origin_user_id not set")));
    
    echo json_encode(getFriendIDS($origin_user_id));
} else
if (strcmp($action, "getfriendlist")==0) {
    $origin_user_id="";
    if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id']; 
    if (strlen($origin_user_id)==0) die(json_encode(array("status" => 0, "msg" => "origin_user_id not set")));
    
    $friendids=getFriendIDS($origin_user_id);
    
    $counter=0;
    
    $friendlist=array();
    for ($i=0;$i<sizeof($friendids);$i++) {
         $sql="SELECT * FROM users WHERE (id=".$friendids[$i].")";
         $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
         if ($row=$query->fetch_assoc()) 
         {
             $friendlist[]=$row['email'];
             $counter++;
         } else {
             $friendlist[]="not found (".$friendids[$i].")";
         }
    }
    echo json_encode(array("status" => 1, "friendlist" => $friendlist));
}  else
if (strcmp($action, "getfriendlistarray")==0) {
    $origin_user_id="";
    if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id']; 
    if (strlen($origin_user_id)==0) die(json_encode(array("status" => 0, "msg" => "origin_user_id not set")));
    
    // optional:
    $invite_to_room="";
    if (isset($_GET['invite_to_room'])) $invite_to_room=$_GET['invite_to_room']; 
    
    
    
    $mode="";
    if (isset($_GET['mode'])) $mode=$_GET['mode']; 
    
    
    if (strcmp($mode,"inverse")!=0) {
        // Get those to which the user ($origin_user_id) is a friend
        $friendids=getFriendIDS($origin_user_id);
    } else {
        // Get those which are no friends of the user ($origin_user_id)
        $friendids=getNOTFriendIDS($origin_user_id);
    }
    
    $counter=0;
    
    $friendlistarray=array();
    for ($i=0;$i<sizeof($friendids);$i++) {
         $sql="SELECT * FROM users WHERE (id=".$friendids[$i].")";
         $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
         if ($row=$query->fetch_assoc()) 
         {
             
             // optional, only when invitation_to_room is set
             if (strlen($invite_to_room)>0) {
                    $sql2="SELECT * FROM room_invitations WHERE (roomid=".$invite_to_room.") AND (invitation_by=".$origin_user_id.") AND (invitation_to=".$row['id'].")";
                    $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
                    if ($row2=$query2->fetch_assoc())
                    {
                                $row['invited']=1;
                    } else {
                                $row['invited']=0;
                    }
             }
             
             
             $friendlistarray[]=$row;
             $counter++;
         }
    }
    echo json_encode(array("status" => 1, "friendlistarray" => $friendlistarray));
} else
if (strcmp($action, "removefromfriendlist")==0) {
    // TODO
} else
if (strcmp($action, "addroom")==0) {
    
    $roomname="";
    if (isset($_GET['roomname'])) $roomname=$_GET['roomname'];
    if (strlen($roomname)==0) die(json_encode(array("status" => 0, "msg" => "roomname not set")));
    
    $owner_user_id="";
    if (isset($_GET['owner_user_id'])) $owner_user_id=$_GET['owner_user_id'];
    if (strlen($owner_user_id)==0) die(json_encode(array("status" => 0, "msg" => "owner_user_id not set")));
    
    $sql="SELECT * FROM rooms WHERE (name='".$roomname."') AND (owner_user_id=".$owner_user_id.")";
         $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
         if ($row=$query->fetch_assoc()) 
         {
             echo json_encode(array("status" => 1, "msg" => "A room with that name already exists"));
         } else {
             
             $sql="INSERT INTO rooms (name,owner_user_id) VALUES ('".$roomname."',".$owner_user_id.")";
             $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
             
             $last_insert_id=DBi::$conn->insert_id;
             
             $sql="INSERT INTO roommembers (origin_room_id,member_id) VALUES ('".$last_insert_id."',".$owner_user_id.")";
             $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
             
             echo json_encode(array("status" => 1, "msg" => "Created room with the name ".$roomname." for the owner with the ID ".$owner_user_id));
             
         }
} else
if (strcmp($action, "getroomlist")==0) {
    
         $owner_user_id="";
         if (isset($_GET['owner_user_id'])) {
             $owner_user_id=$_GET['owner_user_id'];
         }
    
         $getinvitationinfo=false;
       
         
         $invitation_by="";
         if (isset($_GET['invitation_by'])) {
             $invitation_by=$_GET['invitation_by'];
             $getinvitationinfo=true;
         }
         
         $friends="";
         if (isset($_GET['friends'])) {
             $friends=$_GET['friends'];
         }
         
         if ($getinvitationinfo==true) { // Check if all parameters are set
             if ( (strlen($invitation_by)==0) || (strlen($owner_user_id)==0) ) {
                 die(json_encode(array("status" => 0, "msg" => "invitation_by or owner_user_id not set")));
             }
         }
    
         $counter=0;
         $roomlist=array();
         $sql="SELECT r.id as r_id, r.*,u.id as u_id, u.* FROM rooms r LEFT JOIN users u ON (r.owner_user_id=u.id)";
        
         if (strcmp($friends,"1")==0) {
             if (strlen($owner_user_id)>0) {
               $sql.=" WHERE ( (owner_user_id=".$owner_user_id.")";
              } else {
                  $sql.="WHERE ( (false)";
              }
              $friendids=getFriendIDS($owner_user_id);
              for ($i=0;$i<sizeof($friendids);$i++) {
                $sql.=" OR (owner_user_id=".$friendids[$i].")";
              }
              $sql.=")";
         } else {
              if (strlen($owner_user_id)>0) {
               $sql.=" WHERE (owner_user_id=".$owner_user_id.")";
              }
         }
        
         $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
         while ($row=$query->fetch_assoc()) 
         {
            
             $member_ids=getRoomMembers($row['r_id'], false);
             $members = getRoomMembers($row['r_id'], true);
             $array=array("name" => $row['name'], "room_id" => $row['r_id'], "member_ids" => $member_ids, "members" => $members, "owner_user_id" => $row['owner_user_id'], "user_id" => $row['u_id'], "email" => $row['email'], "title" => $row['title'], "first_name" => $row['first_name'], "last_name" => $row['last_name']);
             
           
             $roomlist[]=$array;
             $counter++;
         }
    echo json_encode(array("status" => 1, "roomlist" => $roomlist));
}  else
if (strcmp($action, "delete_room")==0) {
    
    $roomid="";
    if (isset($_GET['roomid'])) $roomid=$_GET['roomid'];
    if (strlen($roomid)==0) die(json_encode(array("status" => 0, "msg" => "No roomid set")));
    
    $sql="DELETE FROM rooms WHERE (id=".$roomid.")";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    
    echo json_encode(array("status" => 1, "msg" => "Deleted"));
    
    
}  else
if (strcmp($action, "joinroom")==0) {
    
    $origin_user_id="";
    if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id']; 
    if (strlen($origin_user_id)==0) die(json_encode(array("status" => 0, "msg" => "origin_user_id not set")));
    
    $roomid="";
    if (isset($_GET['roomid'])) $roomid=$_GET['roomid']; 
    if (strlen($roomid)==0) die(json_encode(array("status" => 0, "msg" => "roomid not set")));
    
    $sql="SELECT * FROM roommembers WHERE (origin_room_id=".$roomid.") AND (member_id=".$origin_user_id.")";
         $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
         if ($row=$query->fetch_assoc()) 
         {
                die(json_encode(array("status" => 0, "msg" => $origin_user_id." is already a member of the room ".$roomid)));
           
         } else {
             $sql="INSERT INTO roommembers (origin_room_id, member_id) VALUES (".$roomid.", ".$origin_user_id.")";
             $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
             
              $sql="UPDATE room_invitations SET confirmed=1 WHERE (roomid=".$roomid.") AND (invitation_to=".$origin_user_id.")";
             $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
         }
    
    echo json_encode(array("status" => 1, "msg" => "OK"));
    
    
}  else
if (strcmp($action, "unjoinroom")==0) {
    
    $origin_user_id="";
    if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id']; 
    if (strlen($origin_user_id)==0) die(json_encode(array("status" => 0, "msg" => "origin_user_id not set")));
    $roomid="";
    if (isset($_GET['roomid'])) $roomid=$_GET['roomid']; 
    if (strlen($roomid)==0) die(json_encode(array("status" => 0, "msg" => "roomid not set")));
    
    $sql="DELETE FROM roommembers WHERE (origin_room_id=".$roomid.") AND (member_id=".$origin_user_id.")";
    
      $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    
    echo json_encode(array("status" => 1, "unjoined" => 1));
    
    
} else if (strcmp($action, "getismember")==0) {
    
    $origin_user_id="";
    if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id']; 
    if (strlen($origin_user_id)==0) die(json_encode(array("status" => 0, "msg" => "origin_user_id not set")));
    $roomid="";
    if (isset($_GET['roomid'])) $roomid=$_GET['roomid']; 
    if (strlen($roomid)==0) die(json_encode(array("status" => 0, "msg" => "roomid not set")));
    
    $sql="SELECT * FROM roommembers WHERE (origin_room_id=".$roomid.") AND (member_id=".$origin_user_id.")";
    
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    if ($row=$query->fetch_assoc()) 
    {
        echo json_encode(array("status" => 1, "joined" => 1, "debug_sql" => $sql));
    } else {
        echo json_encode(array("status" => 1, "joined" => 0, "debug_sql" => $sql));
    }
    
    
} else
if (strcmp($action, "getjoinedrooms")==0) {
    $origin_user_id="";
    if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id']; 
    if (strlen($origin_user_id)==0) die(json_encode(array("status" => 0, "msg" => "origin_user_id not set")));
    
    $sql="SELECT * FROM roommembers WHERE (member_id=".$origin_user_id.")";
    
    $roomids=array();
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    while ($row=$query->fetch_assoc()) 
    {
        $roomids[]=$row['origin_room_id'];
    }
    
    $roomlist=array();
    for ($i=0;$i<sizeof($roomids);$i++) {
         $sql="SELECT * FROM rooms WHERE (id=".$roomids[$i].")";
         $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
         if ($row=$query->fetch_assoc()) 
         {
             $roomlist[]=$row['name'];
             
         } else {
             $roomlist[]="not found (".$friendids[$i].")";
         }
    }
    echo json_encode(array("status" => 1, "roomlist" => $roomlist));
} else
if (strcmp($action, "getuser")==0) {
    $id="";
    if (isset($_GET['id'])) $id=$_GET['id']; 
    if (strlen($id)==0) die(json_encode(array("status" => 0, "msg" => "id not set")));
    
    $sql="SELECT * FROM users WHERE (id=".$id.")";
    
    $roomids=array();
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    if ($row=$query->fetch_assoc()) 
    {
       
        $id=$row['id'];
        $title=$row['title'];
        $first_name=$row['first_name'];
        $last_name=$row['last_name'];
        $email=$row['email'];
        $md5pwd=$row['md5pwd'];
        $avatar_url=$row['avatar_url'];
        $sex=$row['sex'];
        
    } else {
        $id=0;
        $title="";
        $first_name="";
        $last_name="";
        $email="";
        $md5pwd="";
        $avatar_url="";
        $sex="0";
    }
    
    echo json_encode(array("id" => $id, "status" => 1, "title" => $title, "first_name" => $first_name, "last_name" => $last_name, "email" => $email, "md5pwd" => $md5pwd, "avatar_url" => $avatar_url, "sex" => $sex));
    die();
} else
if (strcmp($action, "updateuser")==0) {
        $id=$_GET['id'];
        $title=$_GET['title'];
        $first_name=$_GET['first_name'];
        $last_name=$_GET['last_name'];
        $email=$_GET['email'];
        $md5pwd=$_GET['md5pwd'];
        $avatar_url=$_GET['avatar_url'];
        $sex=$_GET['sex'];
        
        $sql="UPDATE users SET title=\"".DBi::$conn->real_escape_string($title)."\", first_name=\"".DBi::$conn->real_escape_string($first_name)."\", last_name=\"".DBi::$conn->real_escape_string($last_name).
              "\", email=\"".DBi::$conn->real_escape_string($email)."\", md5pwd=\"".DBi::$conn->real_escape_string($md5pwd)."\", avatar_url=\"".DBi::$conn->real_escape_string($avatar_url)."\", sex=\"".DBi::$conn->real_escape_string($sex)."\" WHERE (id=".$id.")";
        
        $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
        echo json_encode(array("status" => 1, "msg" => "OK"));
} else
if (strcmp($action, "get_room")==0) {
    
    $roomid="";
    if (isset($_GET['roomid'])) $roomid=$_GET['roomid'];
    if (strlen($roomid)==0) { echo json_encode(array("status" => 0, "msg" => "No roomid set")); exit; }
    
    $sql="SELECT * FROM rooms WHERE (id=".$roomid.")";
    
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    if ($row=$query->fetch_assoc()) 
    {
            echo json_encode(array("status" => 1, "name" => $row['name'], "id" => $row['id']));
    } else {
        echo json_encode(array("status" => 0, "msg" => "Room not found"));
    }
     
} else
if (strcmp($action, "update_room")==0) {
        $id="";
        $name="";
        if (isset($_GET['id'])) $id=$_GET['id'];
        if (isset($_GET['name'])) $name=$_GET['name'];
        if (strlen($id)==0) { echo json_encode(array("status" => 0, "msg" => "No id set")); exit; }
        if (strlen($name)==0) { echo json_encode(array("status" => 0, "msg" => "No name set")); exit; }
        
        $sql="UPDATE rooms SET name=\"".DBi::$conn->real_escape_string($name)."\" WHERE (id=".$id.")";
    
        $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
        echo json_encode(array("status" => 1, "msg" => "OK"));
} else
    if (strcmp($action, "delete_user")==0) {
        $id=$_GET['id'];
        
        $sql="DELETE FROM users WHERE (id=".$id.")";
    
        $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
        echo json_encode(array("status" => 1, "msg" => "OK"));
} else if (strcmp($action, "getuserlist")==0) {
    
         $sortout_friends=false;
         $attribute="";
         if (isset($_GET['attribute'])) $attribute=$_GET['attribute'];
         
         $attribute2="";
         if (isset($_GET['attribute2'])) $attribute2=$_GET['attribute2'];
         
         $select1="";
         $pos=strpos($attribute, "NOT_FRIEND");
         if (is_int($pos)) {
            
             if (strlen($attribute2)==0) {
                 die(json_encode(array("status" => 0, "msg" => "No attribute2 set")));
             }
             $sortout_friends=true;
             $select1=" WHERE (id!=".$attribute2.")";
         }
         $sortout_invited=false;
         $pos=strpos($attribute, "NOT_INVITED");
         if (is_int($pos)) {
              $sortout_invited=true;
              if (strlen($attribute2)==0) {
                 die(json_encode(array("status" => 0, "msg" => "No attribute2 set")));
             }
         }
         
         $userlist=array();
         $sql="SELECT * FROM users".$select1;
         $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
         while ($row=$query->fetch_assoc())
         {
             $add=true;
             if ($sortout_friends) {
                 
                 $sql2="SELECT * FROM friends WHERE ( (origin_user_id=".$attribute2.") AND (destination_user_id=".$row['id'].") ) OR ( (origin_user_id=".$row['id'].") AND (destination_user_id=".$attribute2.") )";
               
                 $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql2);
                 if ($row2=$query2->fetch_assoc()) {
                    $add=false;
                   
                 }
             }
             if ($sortout_invited) {
                 $sql2="SELECT * FROM friend_invitations WHERE (origin_user_id=".$attribute2.") AND (destination_user_id=".$row['id'].")";
               
                 $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql2);
                 if ($row2=$query2->fetch_assoc()) {
                    $add=false;
                   
                 }
             }
             
             if ($add) $userlist[]=array("id" => $row['id'], "title" => $row['title'], "first_name" => $row['first_name'], "last_name" => $row['last_name'], "email" => $row['email'], "avatar_url" => $row['avatar_url'], "sex" => $row['sex']);
         }

            echo json_encode(array("status" => 1, "userlist" => $userlist));
} else if (strcmp($action, "inviteuser")==0) {
    
        $invitation_by="";
        if (isset($_GET['invitation_by'])) $invitation_by=$_GET['invitation_by'];
        if (strlen($invitation_by)==0) { echo json_encode(array("status" => 0, "msg" => "Not invitation_by set")); exit; }
        
        $invitation_to="";
        if (isset($_GET['invitation_to'])) $invitation_to=$_GET['invitation_to'];
        if (strlen($invitation_to)==0) { echo json_encode(array("status" => 0, "msg" => "Not invitation_to set")); exit; }
        
        $roomid="";
        if (isset($_GET['roomid'])) $roomid=$_GET['roomid'];
        if (strlen($roomid)==0) { echo json_encode(array("status" => 0, "msg" => "No roomid set")); exit; }
    
        $sql="SELECT * FROM room_invitations WHERE (roomid=".$roomid.") AND (invitation_by=".$invitation_by.") AND (invitation_to=".$invitation_to.")";
        $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
        if ($row=$query->fetch_assoc()) {
                echo json_encode(array("status" => 0, "msg" => "This invitation to that room already exists"));   
                die();
        } else {
        
        $sql="INSERT INTO room_invitations (roomid, invitation_by, invitation_to) VALUES (".$roomid.", ".$invitation_by.", ".$invitation_to.")";
        $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);

        }
        
        echo json_encode(array("status" => 1, "msg" => "OK"));
} else if (strcmp($action, "getuserrow")==0) {
    
        $id="";
        if (isset($_GET['id'])) $id=$_GET['id'];
        if (strlen($id)==0) { echo json_encode(array("status" => 0, "msg" => "No id set")); exit; }
    
        
        
         $sql="SELECT * FROM users WHERE (id=".$id.")";
         $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
         if ($row=$query->fetch_assoc())
         {
             $userrow=array("id" => $row['id'], "title" => $row['title'], "first_name" => $row['first_name'], "last_name" => $row['last_name'], "email" => $row['email'], "avatar_url" => $row['avatar_url'], "sex" => $row['sex']);
         }

    echo json_encode(array("status" => 1, "userrow" => $userrow));
} else if (strcmp($action, "sendfriendrequest")==0) {
    
        $origin_user_id="";
        if (isset($_GET['origin_user_id'])) $origin_user_id=$_GET['origin_user_id'];
        if (strlen($origin_user_id)==0) { echo json_encode(array("status" => 0, "msg" => "No origin_user_id set")); exit; }
    
        $destination_user_id="";
        if (isset($_GET['destination_user_id'])) $destination_user_id=$_GET['destination_user_id'];
        if (strlen($destination_user_id)==0) { echo json_encode(array("status" => 0, "msg" => "No destination_user_id set")); exit; }
        
        $reason="";
        if (isset($_POST['reason'])) $reason=$_POST['reason'];
        
        $sql="SELECT * FROM friends WHERE ( (origin_user_id=".$origin_user_id.") AND (destination_user_id=".$destination_user_id.") ) OR ( (destination_user_id=".$origin_user_id.") AND (origin_user_id=".$destination_user_id.") )";
        $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
        if ($row=$query->fetch_assoc()) {
             echo json_encode(array("status" => 0, "msg" => "Both users are already friends - no invitation to be created"));
             die();
        }
                  
        
        $sql="SELECT * FROM friend_invitations WHERE (origin_user_id=".$origin_user_id.") AND (destination_user_id=".$destination_user_id.")";
        $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
        if ($row=$query->fetch_assoc()) 
        {
             echo json_encode(array("status" => 0, "msg" => "An invitation with the user and the to be invited person already exists"));
        } else {
             
             $sql="INSERT INTO friend_invitations (origin_user_id,destination_user_id,reason) VALUES ('".$origin_user_id."',".$destination_user_id.", \"".DBi::$conn->real_escape_string($reason)."\")";
             $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
             echo json_encode(array("status" => 1, "msg" => "Created an invitation by the owner with the ID ".$origin_user_id." for ".$destination_user_id));
             
        }

}   else
if (strcmp($action, "getfriendrequestlistarray")==0) {
    $user_id="";
    if (isset($_GET['user_id'])) $user_id=$_GET['user_id']; 
    if (strlen($user_id)==0) die(json_encode(array("status" => 0, "msg" => "user_id not set")));
    
    $direction="both";
    if (isset($_GET['direction'])) $user_id=$_GET['direction']; 
    
    $where="";
    if (strcmp($direction,"incoming")==0)
    {
            $where="(destination_user_id=".$user_id.")";
    } else
    if (strcmp($direction,"outgoing")==0) {
            $where="(origin_user_id=".$user_id.")";
            
    } else
    if (strcmp($direction,"both")==0) {
            $where="(destination_user_id=".$user_id.") OR (origin_user_id=".$user_id.")";
           
    }
    
    $friendrequestliarray=array();
    $sql="SELECT * FROM friend_invitations WHERE ".$where; 
   
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    while ($row=$query->fetch_assoc()) 
    {
           if ($row['destination_user_id']==$user_id) {
               $row['direction']="incoming";
               $field_id=$row["origin_user_id"];
           } else if ($row['origin_user_id']==$user_id) {
               $row['direction']="outgoing";
               $field_id=$row["destination_user_id"];
           } 
           
           $sql2="SELECT * FROM users WHERE (id=".$field_id.")";
           $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
           if ($row2=$query2->fetch_assoc()) {
               $row['email']=$row2['email'];
               $row['title']=$row2['title'];
               $row['first_name']=$row2['first_name'];
               $row['last_name']=$row2['first_name'];
           }
        
           $friendrequestlistarray[]=$row;
    }
    
    
    echo json_encode(array("status" => 1, "friendrequestlistarray" => $friendrequestlistarray));
} else
if (strcmp($action, "confirmfriendrequest")==0) {
    $id="";
    if (isset($_GET['id'])) $id=$_GET['id']; 
    if (strlen($id)==0) die(json_encode(array("status" => 0, "msg" => "id not set")));
    
    $sql="SELECT * FROM friend_invitations WHERE (id=".$id.")";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    if ($row=$query->fetch_assoc()) {
        
            $sql2="UPDATE friend_invitations SET confirmed=1 WHERE (id=".$id.")";
            $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql2);
    
            $sql2="INSERT INTO friends (origin_user_id, destination_user_id) VALUES (".$row['origin_user_id'].", ".$row['destination_user_id'].")";
            $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql2);
        
    }
    
  
    
    
    echo json_encode(array("status" => 1, "msg" => "OK"));
}
  else
if (strcmp($action, "getroominvitationlistarray")==0) {
    $user_id="";
    if (isset($_GET['user_id'])) $user_id=$_GET['user_id']; 
    if (strlen($user_id)==0) die(json_encode(array("status" => 0, "msg" => "user_id not set")));
    
    
    $roominvitationlistarray=array();
    $sql="SELECT ri.*,r.*,ri.id as invitation_id, r.id as room_id FROM room_invitations ri LEFT JOIN rooms r ON (ri.roomid=r.id) WHERE (invitation_by=".$user_id.") OR (invitation_to=".$user_id.")"; 
   
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    while ($row=$query->fetch_assoc()) 
    {
           $direction="incoming";
           if ($row['invitation_to']==$user_id) {
               $field_id=$row["invitation_by"];
           } else if ($row['invitation_by']==$user_id) {
               $direction="outgoing";
               $field_id=$row["invitation_to"];
           }
           $row['direction']=$direction;
           
           // Check if we are already in the room
           
           if (strcmp($direction, "incoming")==0) {
               
               $sql2="SELECT * FROM roommembers WHERE (origin_room_id=".$row['roomid'].") AND (member_id=".$user_id.")";
               $row['sql_debug']=$sql2;
               $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
               if ($row2=$query2->fetch_assoc()) {
                   $row['already_room_member']=1;
               } else {
                   $row['already_room_member']=0;
               }
           }
           
           
           $sql2="SELECT * FROM users WHERE (id=".$field_id.")";
         
           $query2=DBi::$conn->query($sql2) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
           if ($row2=$query2->fetch_assoc()) {
               $row['email']=$row2['email'];
               $row['title']=$row2['title'];
               $row['first_name']=$row2['first_name'];
               $row['last_name']=$row2['first_name'];
           }
        
           $roominvitationlistarray[]=$row;
    }
    
    
    echo json_encode(array("status" => 1, "roominvitationlistarray" => $roominvitationlistarray));
} 
  
    else
if (strcmp($action, "delete_room_invitation")==0) {
    
    $room_invitation_id="";
    if (isset($_GET['room_invitation_id'])) $room_invitation_id=$_GET['room_invitation_id'];
    if (strlen($room_invitation_id)==0) die(json_encode(array("status" => 0, "msg" => "No room_invitation_id set")));
    
    $sql="DELETE FROM room_invitations WHERE (id=".$room_invitation_id.")";
    $query=DBi::$conn->query($sql) or die(DBi::$conn->error." ".__FILE__." line ".__LINE__.$sql);
    
    echo json_encode(array("status" => 1, "msg" => "Deleted"));
    
    
}
    