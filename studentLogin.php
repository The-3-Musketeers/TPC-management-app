<?php
    $page_title = 'Login';
    require_once('templates/header.php');
    require_once('templates/navbar.php');?>
    <br>

    <script>

    </script>

    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Roll Number:</label>
            <div class="col-sm-6">
            <input type="email" class="form-control" id="email" placeholder="Enter roll number">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Password:</label>
            <div class="col-sm-6">
            <input type="password" class="form-control" id="pwd" placeholder="Enter password">
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

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
            <div>
                <label>New user? Signup <a href="./studentSignup.php">here</a></label>
            </div>
            </div>
        </div>
    </form>
<?php require_once('templates/footer.php');?>
