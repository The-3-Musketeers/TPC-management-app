# TPC-management-app
Training and Placement Cell for college

## Steps to setup the TPC web app(locally):
- Download and setup the latest stable release of XAMPP/LAMPP/MAMPP/WAMPP from [here](https://www.apachefriends.org/download.html) depending on your OS.
- Clone the repository into the htdocs folder of your server.
- Create the connectVars.php file in the main directory. This file contains connection constants for the SQL database.

```php
<?php
  define('DB_HOST', '');
  define('DB_USER', '');
  define('DB_PASSWORD', '');
  define('DB_NAME', '');
?>
```
- Setup your database by either importing the TPC_DB.sql file using phpMyAdmin or manually copy and paste the code in the SQL terminal.

## Setup the connection variables(in connectVars.php):
- DB_HOST - Url of the SQL database server
- DB_USER - Username
- DB_PASSWORD - Password
- DB_NAME - Name of the database

## Steps to start the web app:
- Start your XAMPP server.
- Go to http://localhost/TPC-management-app/ on your browser to open the app.
