# restfrooms
A PHP REST interface for working with friends and rooms. Currently without user authentification.

basics

- users: you can create new users, fields: email, title, first name, last name, sex
- login as user or admin (localStorage)
- logout user or admin (localStorage)

Friends

- send friend requests
- show open friend requests
- confirm send request
- add friends
- remove friends

Rooms

- create new rooms
- invite friends to rooms
- join rooms
- unjoin rooms

The most important code is contained in rest.php, which is the rest interface that you can use to do the
mentioned things above. The other code is just an example how it works by making ajax requests to the rest
framework.

Execute backend.sql in phpmyadmin and edit connect_db_settings.php to configure establishing the connection to the DB.

You can find the working demo application on https://backend-sconfig.rhcloud.com/admin.php.