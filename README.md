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
- Create the appVars.php file in the main directory. This file contains application constants.
```
<?php
  // Define Captcha font
  define('CAPTCHA_FONT', 'Courier New Bold.ttf');
?>
```
- Setup your database by either importing the TPC_DB.sql file using phpMyAdmin or manually copy and paste the code in the SQL terminal.

## Setup the connection variables(in connectVars.php):
- DB_HOST - Url of the SQL database server
- DB_USER - Username
- DB_PASSWORD - Password
- DB_NAME - Name of the database

## Setup the app variables(in appVars.php):
- Download the font file([Courier New Bold.ttf](https://fontzone.net/font-details/courier-new-bold)) or any other font for captcha generation into the util directory.
- CAPTCHA_FONT - Name of the font file for captcha

## Other libraries / Resources required:
- Create a directory "lib"  in the main directory and download the [tcpdf](https://tcpdf.org/) library in it.

## Optional:
- If your app throws error during uploading photos/resumes by student or company, try creating images and resume directory manually and then create two subdirectories recruiters and students inside the images directory.

## Steps to start the web app:
- Start your XAMPP server.
- Go to http://localhost/TPC-management-app/ on your browser to open the app.
