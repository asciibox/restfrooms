<head>
    <script>
        window.onload = function() {
           
            
                if ( (localStorage.getItem("email")==null) || (localStorage.getItem("md5pwd")==null) ) {
                    window.location.href="login.php";
                } else
                if ( (localStorage.getItem("email")=="admin") && (localStorage.getItem("md5pwd")=="admin") ) {
                   window.location.href="admin.php";
               } else window.location.href="showusers.php";
        }
    </script>
</head>
<body></body>