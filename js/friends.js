 function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

    function getFriendList(origin_user_id, mode, invite_by_user, invite_to_room) {
        
         if (typeof(mode)=="undefined") mode="";
        
         if (!isNumeric(origin_user_id)) {
             alert("Please provide a valid origin_user_id");
             return;
         }
         
         var invite_to_room_parameter="";
         if (mode=="INVITE") {
             invite_to_room_parameter="&invite_to_room="+invite_to_room;
         }
        
         counter=0;
         $.ajax({
                        url: "rest.php?action=getfriendlistarray&origin_user_id="+origin_user_id+invite_to_room_parameter,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                         
                           if (responseText.friendlistarray.length==0) {
                               
                               $('#friendsinfo').html("<span style='color:red'>Sorry, this user has no friends</span>");
                           }
                            
                           for (var i = 0; i < responseText.friendlistarray.length; i++) {
                              
                              var friendarray=responseText.friendlistarray[i];
                              var id = friendarray.id;
                              var name = friendarray.name;
                              var email = friendarray.email;
                              var title = friendarray.title;
                              var first_name = friendarray.first_name;
                              var last_name = friendarray.last_name;
                              
                              var additional="";
                              
                              if (mode=="INVITE") {
                                  if (friendarray.already_room_member==1) {
                                    additional="<td>Already a member of this room</td>";
                                  } else
                                  if (friendarray.invited==0) {
                                    additional="<td><input type='button' id='invitebutton"+id+"' value='invite' onclick='inviteUserToRoom("+invite_by_user+", "+id+", "+invite_to_room+")'></td>";
                                  } else {
                                    additional="<td>Already invited</td>";
                                  }
                              }
                              
                              var id = 
                               $('#friendstable').append("<tr><td>"+id+"</td><td>"+email+"</td><td>"+title+"</td><td>"+first_name+"<td>"+last_name+"</td>"+additional+"</tr>");
                             
                        } // for
                        } else {
                            alert(responseText.msg);
                        }
                    });
           
    }
    
    
       function getFriendRequestList(user_id) {
        
         if (!isNumeric(user_id)) {
             alert("Please provide a valid origin_user_id");
             return;
         }
        
         counter=0;
         $.ajax({
                        url: "rest.php?action=getfriendrequestlistarray&user_id="+user_id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                         
                            for (var i = 0; i < responseText.friendrequestlistarray.length; i++) {
                               var array = responseText.friendrequestlistarray[i];
                               var id = array.id;
                               var email = array.email;
                               var title = array.title;
                               var first_name = array.first_name;
                               var last_name = array.last_name;
                               var reason = array.reason;
                               var direction = array.direction;
                               var confirm="";
                             
                                   if (array.confirmed==0) {
                                     if (array.direction=='incoming') { confirm="<input id='confirmbutton"+id+"' type='button' value='confirm' onclick='confirm("+id+")'>"; }
                                   } else confirm="Confirmed";
                                             
                               $('#friendrequesttable').append("<tr><td>"+reason+"<td>"+direction+"</td><td>"+id+"</td><td>"+email+"</td><td>"+title+"</td><td>"+first_name+"<td>"+last_name+"</td><td>"+confirm+"</tr>");
                           }
                        
                        } else {
                            alert(responseText.msg);
                        }
                    });
           
    }
    
    
    function confirm(id) {
        
         $.ajax({
                        url: "rest.php?action=confirmfriendrequest&id="+id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                         
                         $('#confirmbutton'+id).parent().html('Confirmed');
                      
                    });
        
    }