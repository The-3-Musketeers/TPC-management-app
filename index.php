<?php
    // Start the session
    require_once('templates/startSession.php');
    $page_title = 'Home';
    require_once('templates/header.php');
    require_once('templates/navbar.php');?>

<!-- Footer -->
<footer class="footer" class="page-footer font-small blue" style="background-color: #e3f2fd;">
    <div class="container">
        <span class="text-muted">Developed by: </span>
        <a href="#" id="link1"><span id="dev1">1</span></a>
        <a href="#" id="link2"><span id="dev2">2</span></a>
        <a href="#" id="link3"><span id="dev3">3</span></a>
    </div>
</footer>

<!-- Footer -->
<?php require_once('templates/footer.php');?>
