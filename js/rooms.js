 function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

    
     function getRoomInfo(id,callback) {
          
        
          $.ajax({
                        url: "rest.php?action=get_room&roomid="+id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                        
                        callback(responseText);
                        
                    });
                    
       
        
    }

 function updateRoom(id,name) {
          $.ajax({
                        url: "rest.php?action=update_room&id="+id+"&name="+name,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                        
                        $('#editor').hide();
                        window.location.reload();
                    });
    }
    
    function editRoom(id) {
        
          $.ajax({
                        url: "rest.php?action=get_room&roomid="+id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                        $('#id').val(responseText.id);
                        $('#roomname').val(responseText.name);
                        $('#editor').show();
                        
                    });
        
    }
    
    function deleteRoom(id) {
        
          $.ajax({
                        url: "rest.php?action=delete_room&roomid="+id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        if (responseText.status == 1) {
                           window.location.reload();
                        } else {
                            alert(responseText.msg);
                        }
                    });
           
        
    }
    function getRoomList(userid,mode,invitation_by) {
        
         if (!isNumeric(userid)) {
             alert("Please provide a valid userid");
             return;
         }
        
         var additional_parameters="";
         if (mode=="INVITE") {
             additional_parameters="&invitation_by="+invitation_by;
         }
         counter=0;
         $.ajax({
                        url: "rest.php?action=getroomlist"+additional_parameters+"&owner_user_id="+userid,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                            var name="";
                          
                          if (responseText.roomlist.length==0) {
                              $('#roominfo').html('There are no room invitations to others by this user');
                          }
                          
                           for (var i = 0; i < responseText.roomlist.length; i++) {
                              
                               var id = responseText.roomlist[i].room_id;
                               var name=responseText.roomlist[i].name;
                               
                               var members=responseText.roomlist[i].members;
                               var email=responseText.roomlist[i].email;
                               var first_name=responseText.roomlist[i].first_name;
                               var last_name=responseText.roomlist[i].last_name;
                               
                               var additional_buttons;
                             
                               if (mode=="INVITE") {
                                    var invited = responseText.roomlist[i].invited;
                                    if (invited==1) 
                                    {
                                        additional_buttons="Already invited";    
                                    } else {
                                      
                                        additional_buttons=("<input type='button' value='Invite this user to rooms on the following page' onclick=\"inviteByUserToRoom("+userid+", "+id+")\">");
                                    }
                               } else
                                    additional_buttons=("<input type='button' value='Delete room' onclick=\"deleteRoom("+id+");\"><input type='button' value='Edit room' onclick=\"editRoom("+id+");\">");
                                   $('#roomtable').append("<tr><td>"+id+"</td><td>"+name+"</td><td>"+members+"</td><td>"+email+" ("+first_name+" "+last_name+")</td><td>"+additional_buttons+"</td></tr>");
                             
                        }
                              
                           
                        } else {
                            alert(responseText.msg);
                        }
                    });
           
    }
    
    function deleteroominvitation(id) {
        
         $.ajax({
                        url: "rest.php?action=delete_room_invitation&room_invitation_id="+id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                            $('#invitationtr'+id).remove();
                        }
                    });
        
    }
    
    
     function getRoomInvitationList(user_id) {
        
         if (!isNumeric(user_id)) {
             alert("Please provide a valid origin_user_id");
             return;
         }
        
         counter=0;
         $.ajax({
                        url: "rest.php?action=getroominvitationlistarray&user_id="+user_id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                         
                            for (var i = 0; i < responseText.roominvitationlistarray.length; i++) {
                               var array = responseText.roominvitationlistarray[i];
                               var id = array.invitation_id;
                               var direction = array.direction;
                               var email = array.email;
                               var title = array.title;
                               var first_name = array.first_name;
                               var last_name = array.last_name;
                               var name = array.name;
                               
                               var confirm="";
                               var dodelete="<input type='button' value='Delete' onclick='deleteroominvitation("+id+");'>";
                               if (direction=="incoming") {
                                  
                                    if (array.confirmed==0) {
                                    confirm="<input type='button' value='Confirm' onclick='confirm("+user_id+", "+id+")'>";
                                    } else {
                                       confirm="Confirmed"; 
                                    }
                               }
                                             
                               $('#roominvitationstable').append("<tr id='invitationtr"+id+"'><td>"+name+"</td><td>"+direction+"</td><td>"+id+"</td><td>"+email+"</td><td>"+title+"</td><td>"+first_name+"<td>"+last_name+"</td><td>"+confirm+dodelete+"</tr>");
                           }
                        
                        } else {
                            alert(responseText.msg);
                        }
                    });
           
    }