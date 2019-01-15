<?php
    $page_title = 'Login - T&amp;P IIT Patna';
    include 'templates/header.php';
    include 'templates/navbar.php';?>
    <br>

    <script>

    </script>

    <form class="form-horizontal" action="">
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Roll Number:</label>
            <div class="col-sm-6">
            <input type="email" class="form-control" id="email" placeholder="Enter roll number">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Full Name:</label>
            <div class="col-sm-6">
            <input type="email" class="form-control" id="email" placeholder="Enter full name">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Webmail ID:</label>
            <div class="col-sm-6">
            <input type="email" class="form-control" id="email" placeholder="Enter webmail id">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Password:</label>
            <div class="col-sm-6">
            <input type="password" class="form-control" id="pwd" placeholder="Enter password">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Comfirm Password:</label>
            <div class="col-sm-6">
            <input type="password" class="form-control" id="pwd" placeholder="Re-Enter password">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label><input type="checkbox"> Remember me</label>
            </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </div>
    </form>
<?php include 'templates/footer.php';?>
