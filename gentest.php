<?php
	ob_start();
	//session_start();
	require_once 'dbconnect.php';
?>


<!DOCTYPE html>
<html>
    <head>
        <meta name=viewport content="width=device-width,initial-scale=1,user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Answer the following questions</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
        <link rel="stylesheet" href="style.css" type="text/css" />
        <link rel="stylesheet" href="assets/css/custom-style.css" type="text/css" />
        <link rel="stylesheet" href="assets/css/font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="assets/css/responsive.css" type="text/css">
    </head>

    <body>

    	<div id="codeChecker" class="testSect">
        	<?php include('includes/codeChecker.php'); ?>
        </div>


        <div id="catListing" class="testSect">
            <?php include('includes/catListing.php'); ?>
        </div>

        <div id="wisdomSect" class="testSect">
            <?php include('includes/wisdomSect.php'); ?>
        </div>

        <div id="respectSect" class="testSect">
            <?php include('includes/respectSect.php'); ?>
        </div>
        
        <div id="sustainabilitySect" class="testSect">
            <?php include('includes/sustainabilitySect.php'); ?>
        </div>

        <div id="humandevelopSect" class="testSect">
            <?php include('includes/humandevelopSect.php'); ?>
        </div>

        <div id="valuequestionSect" class="testSect">
            <?php include('includes/valuequestionSect.php'); ?>
        </div>

        <div id="thankuSect" class="testSect">
            <?php include('includes/thankuSect.php'); ?>
        </div>
        
        <span id="codeCheck" data-attr_code=""></span>
        <script src="assets/jquery-1.11.3-jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/custom.js"></script>
        
    </body>
</html>
<?php ob_end_flush(); ?>