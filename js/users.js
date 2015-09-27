 function getUserList(buttontitle, attribute,attribute2) {
        
        var params="";
        if (typeof(attribute)=="undefined") attribute="";
        if (typeof(attribute2)=="undefined") attribute2="";
        
         if (attribute.length>0) params="&attribute="+attribute+"&attribute2="+attribute2;
        
     
         $.ajax({
                        url: "rest.php?action=getuserlist"+params,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                           for (var i = 0; i < responseText.userlist.length; i++) {
                              
                               var useritem = responseText.userlist[i];
                               $('#usertable').append("<tr><td><input id='invitebutton"+useritem.id+"' type='button' value='"+buttontitle+"' onclick=\"invite("+useritem.id+");\"></td><td>"+useritem.id+"</td><td>"+useritem.email+"</td><td>"+useritem.title+"</td><td>"+useritem.first_name+"</td><td>"+useritem.last_name+"</td><td>"+useritem.avatar_url+"</td><td>"+useritem.sex+"</td></tr>");
                             
                           }
                        } else {
                            alert(responseText.msg);
                        }
                    });
                    
     
        
    }
    
    
     function getUserInfo(id,callback) {
          
        
          $.ajax({
                        url: "rest.php?action=getuser&id="+id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                        
                        callback(responseText);
                        
                    });
                    
       
        
    }