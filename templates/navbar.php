<div class="container bloc-sm">
  <div class="page-header" style="padding-top: 10px; padding-bottom: 10px;">
    <div style="display: flex;">
      <a href="./index.html"><img src="./pictures/iitp_logo.png" height="80"></a>
        <div style="padding-bottom: 5px; padding-left: 20px; padding-top: 5px;">
          <h3>Training &amp; Placement Cell</h3>
          <h5><strong>Indian Institute of Technology Patna</strong></h5>
        </div>
    </div>
  </div>
</div>
<div class="container">

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="./index.php">Home</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="#">Why IIT Patna?</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="#">Departments</a>
      </li>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Contact Us
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Email</a>
          <a class="dropdown-item" href="#">Phone</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Set Appointment</a>
        </div>
      </li>
    </ul>
    <span class="navbar-text">
      <ul class="navbar-nav mr-auto">
        <?php if(!isset($_SESSION['user_id'])){ ?>
          <li class="nav-item active">
            <a class="nav-link" href="#">Recruiter Login</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="./studentLogin.php">Student Login</a>
          </li>
        <?php
          } else{
        ?>
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $_SESSION['username']; ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">Profile</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="./logout.php">Logout</a>
          </div>
        </li>
      </ul>
    </span>
  </div>
</nav>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="./index.php">Home</a></li>
        <li><a href="#">Why IIT Patna?</a></li>
        <li><a href="#">Departments</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#"></a></li>
            <li><a href="#">Phone</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Set Appointment</a></li>
          </ul>
        </li>
        <ul class="nav navbar-nav navbar-right">
        
          <li><a href="#">Recruiter Login</a></li>
          <li><a href="./studentLogin.php">Student Login</a></li>
        
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['username']; ?><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="./studentProfile.php">Profile</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="./logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
        <?php } ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</div>
<br />
