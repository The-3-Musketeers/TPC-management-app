<?php
  // Start the session
  require_once('../templates/startSession.php');

  // import app variables
  require_once('../appVars.php');

  define('CAPTCHA_WIDTH', 180);
  define('CAPTCHA_HEIGHT', 40);
  define('CAPTCHA_NUMCHARS', 6);
  define('CAPTCHA_LINES', 15);
  define('CAPTCHA_DOTS', 1000);

  $passphrase = "";
  for($i=0; $i<CAPTCHA_NUMCHARS; $i++) {
    $passphrase .= chr(rand(97, 122));
  }
  $_SESSION['passphrase'] = SHA1($passphrase);

  $img = imagecreatetruecolor(CAPTCHA_WIDTH, CAPTCHA_HEIGHT);
  $bg_color = imagecolorallocate($img, 255, 255, 255);
  $text_color = imagecolorallocate($img, 0, 0, 0);
  $graphic_color = imagecolorallocate($img, 34, 34, 34);

  imagefilledrectangle($img, 0, 0, CAPTCHA_WIDTH, CAPTCHA_HEIGHT, $bg_color);
  for ($i=0; $i<CAPTCHA_LINES; $i++) {
    imageline($img, 0, rand() % CAPTCHA_HEIGHT, CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color);
  }
  for ($i=0; $i<CAPTCHA_DOTS; $i++) {
    imagesetpixel($img, rand() % CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color);
  }
  imagettftext($img, 34, 0, 5, CAPTCHA_HEIGHT - 10, $text_color, CAPTCHA_FONT, $passphrase);

  header("Content-type: image/png");
  imagepng($img);
  imagedestroy($img);
?>
