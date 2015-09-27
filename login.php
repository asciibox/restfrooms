<link href="body.css" rel="stylesheet" type="text/css" media="screen" />
<body onload="$('#email').focus();">
<script src="js/jquery.min.js"></script>
<script src="js/md5.js"></script>
<script>
    function login(email, md5pwd) {
        if ( (email=="admin") && (md5pwd=="admin") ) {
            localStorage.setItem("email", "admin");
            localStorage.setItem("md5pwd", "admin");
            window.location.href="admin.php";
        }
         md5pwd = CryptoJS.MD5(md5pwd);    
         $.ajax({
                        url: "rest.php?action=login&email="+email+"&password="+md5pwd,
                        type: "GET",
                        dataType: "json",
                        processData: false
                    }).done(function(responseText) {
                         
                        if (responseText.status == 1) {
                            localStorage.setItem("email", email);
                            localStorage.setItem("password", md5pwd);
                            localStorage.setItem("userid", responseText.userid);
                            window.location.href="showusers.php";
                        } else {
                            alert(responseText.msg);
                        }
                    });
        
    }
   
</script>
Admin username: admin Password: admin
email address: <input type="text" id="email" value="">
password: <input type="text" id="password" value="">
<input type="button" onclick="login($('#email').val(), $('#password').val());" value="Submit"><input type="button" onclick="window.location.href='index.php';" value="Back">
</body>