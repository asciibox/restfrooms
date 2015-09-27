function getFriendlistArray(origin_user_id) {
         $.ajax({
                        url: "rest.php?action=getfriendlistarray&origin_user_id="+origin_user_id,
                        type: "GET",
                        async : false,
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        
                        if (responseText.status == 1) {
                           if (responseText.friendlistarray.length==0) $('#friendlistarray_info').html('You have no friends');
                           for (var i = 0; i < responseText.friendlistarray.length; i++) {
                              
                              var friendlist = responseText.friendlistarray[i];
                              $('friendlistarray_table').append("<tr><td>"+friendlist.id+"</td><td>"+friendlist.email+"</td><td>"+friendlist.title+"</td><td>"+friendlist.first_name+"</td><td>"+friendlist.last_name+"</td><td>"+friendlist.avatar_url+"</td><td>"+riendlist.sex+"</td></tr>");
                           }
                        } else {
                            alert(responseText.msg);
                        }
                    });
}
    