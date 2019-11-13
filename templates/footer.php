<?php
  $url = $_SERVER['REQUEST_URI'];

  $isHome = FALSE;
  if($url == "/TPC-management-app/") $isHome = TRUE;
  
  $url = explode("/", $url);
  if(sizeof($url)>2){
    $folderName = $url[sizeof($url)-2];
  }else{
    $folderName = "";
  }
  $fileName = $url[sizeof($url)-1];
?>
<?php if($fileName == "index.php" || $fileName == "login.php" || $isHome){ ?>
  <!-- Footer -->
  <script src="/TPC-management-app/scripts/footer.js"></script>
<?php } ?>
  <!-- jQuery, Popper.js, Bootstrap JS, Chart.js -->
  <script src="/TPC-management-app/scripts/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="/TPC-management-app/scripts/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="/TPC-management-app/scripts/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  <script src="/TPC-management-app/scripts/Chart.min.js"></script>
  <!-- Custom -->
<?php if($fileName == "profile.php" && $folderName == "student"){ ?>
  <script src="/TPC-management-app/scripts/studentProfile.js"></script>
<?php } ?>
<?php if($fileName == "login.php" && $folderName == "student"){ ?>
  <script src="/TPC-management-app/scripts/studentLogin.js"></script>
<?php } ?>
<?php if($fileName == "createPosition.php"){ ?>
  <script src="/TPC-management-app/scripts/createPosition.js"></script>
<?php } ?>
<?php if($fileName == "editJob.php"){ ?>
  <script src="/TPC-management-app/scripts/editJob.js"></script>
<?php } ?>
  <script src="/TPC-management-app/scripts/index.js"></script>
<?php if($fileName == "signup.php"){ ?>
  <script src="/TPC-management-app/scripts/signup.js"></script>
<?php } ?>
<?php if($fileName == "job.php"){ ?>
  <script src="/TPC-management-app/scripts/stats/job.js"></script>
<?php } ?>
<?php if($fileName == "dashboard.php" && $folderName == "admin"){ ?>
  <script src="/TPC-management-app/scripts/stats/dashboard.js"></script>
<?php } ?>
</body>
</html>
