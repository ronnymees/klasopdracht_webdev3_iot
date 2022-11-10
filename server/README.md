# Server-side

This is the actual application that needs to run on the server.
All .php files need to be deployed to the root on the server under a web application project folder.

## index.php

This is the landing page of the application with general info of the project

## register.php

Used to register a new user (a user = sensor exept for the admin user)

## login.php

Lets the user login and redirect them to the info page

## info.php

The logged in user can see there info and can display sensor data.
The admin can also delete sensor data.

## webapidoc.php

Info about the use of the web API.

## webapi.php

Can be used by a client application to get sensor data for a given periode.

## write.php

Can be used by a iot device to register sensor data via a API key.