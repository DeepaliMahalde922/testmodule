<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
    include 'smtp-mail.php';
	
	// if session is not set this will redirect to login page
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}
	// select loggedin users detail
	$res=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user']);
	$userRow=mysql_fetch_array($res);



    /*error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);*/


    $error = false;

    function generateRandomString($length = 10) {
        $uniqueCode = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);

        $query=mysql_query("SELECT userId FROM users WHERE userCode = '$uniqueCode'");
        $userData = mysql_fetch_array($query);
        $userId = $userData['userId'];
        if($userId){
            generateRandomString($length = 10, $userCode);
        }
        return $uniqueCode;
    }

    if ( isset($_POST['btn-signup']) ) {

        $random = '';
        
        // clean user inputs to prevent sql injections
        $name = trim($_POST['name']);
        $name = strip_tags($name);
        $name = htmlspecialchars($name);

        $phone = trim($_POST['phone']);
        $phone = strip_tags($phone);
        $phone = htmlspecialchars($phone);
        
        $email = trim($_POST['email']);
        $email = strip_tags($email);
        $email = htmlspecialchars($email);
        
        // basic name validation
        if (empty($name)) {
            $error = true;
            $nameError = "Please enter your full name.";
        } else if (strlen($name) < 3) {
            $error = true;
            $nameError = "Name must have atleat 3 characters.";
        } else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
            $error = true;
            $nameError = "Name must contain alphabets and space.";
        }

        // basic number validation Commented
        /*if (empty($phone)) {
            $error = true;
            $phoneError = "Please enter phone number.";
        } else if(!preg_match('/^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{7})$/', trim($phone))) {
            $error = true;
            $phoneError = "Please enter a valid phone number";
        }*/
        
        //basic email validation Commented
        /*if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $error = true;
            $emailError = "Please enter valid email address.";
        } else {
            // check email exist or not
            $query = "SELECT userEmail FROM users WHERE userEmail='$email'";
            $result = mysql_query($query);
            $count = mysql_num_rows($result);
            if($count!=0){
                $error = true;
                $emailError = "Provided Email is already in use.";
            }
        }*/
        
        
        // if there's no error, continue to signup
        if( !$error ) {

            $random = generateRandomString();
            $useremail = $email;

            $chkQuery = "SELECT userLimit FROM userlimit";
            $chkque = mysql_query($chkQuery);
            $limitUserarr = mysql_fetch_array($chkque);
            $limitUser = $limitUserarr['userLimit'];

            $countquery =  mysql_query("SELECT COUNT(*) FROM users");
            $totalUser = mysql_fetch_array($countquery);
            
            if($totalUser[0] && $limitUser){
                if( $limitUser > $totalUser[0]){
                    
                    $query = "INSERT INTO users(userName,userEmail,userPhone,userCode, userStatus) VALUES('$name','$email','$phone','$random', 0)";
                    $res = mysql_query($query);
                        
                    if ($res) {
                        $errTyp = "success";
                        $errMSG = "Successfully registered.";
                        unset($name);
                        unset($email);
                    } else {
                        $errTyp = "danger";
                        $errMSG = "Something went wrong, try again later...";   
                    } 

                }else{
                    $codeTyp = 'error';
                    $codeMSG = "Limit excceds.l";
                }
            }
            
              
        }
        
    }

    if(isset($_POST['btn-email'])){//to run PHP script on submit
        if(!empty($_POST['check_list'])){
            $useremail = $_POST['useremail'];
            $usercode = $_POST['usercode'];

            $codeTyp = $codeMSG = '';
          
            if($_POST['check_list'] == 'true'){

                $from = 'admin@gmail.com';

                $to      = $useremail;
                $subject = 'Thank You';
                $body = '<div>
                            <p>Hello,</p>
                            <p>You are registered for Scholarship Quiz. Please use below link and code to start the quiz:</p>

                            <p>Link: <a href="http://addon.testyou.in/modul/gentest.php">http://addon.testyou.in/modul/gentest.php</a></p>
                            <p><b>Code: </b>'.$usercode.'</p>

                            <p>Thanks</p>
                            <p>Online Test - Support Team</p>';

                  /*$headers  = "From: <$sendto>\r\n";
                  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                  $headers .= "Reply-To: <$sendto>\r\n";
                  */

                $SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body);
                $SMTPChat = $SMTPMail->SendMail();

                $codeTyp = 'success';
                $codeMSG = "Successfully email";
                
                /*if( mail($to, $subject, $message, $headers) )
                {
                    $codeTyp = 'success';
                    $codeMSG = "Successfully email";
                }else{
                    $codeTyp = 'error';
                    $codeMSG = "Something went wrong";
                }*/


            }
            
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta name=viewport content="width=device-width,initial-scale=1,user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome - <?php echo $userRow['userEmail']; ?></title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="">Home</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="/modul/userlist.php">List User</a></li>
            <li><a href="/modul/userattempt.php">User attempt test</a></li>
            <li><a href="/modul/gentest.php">Test</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			  <span class="glyphicon glyphicon-user"></span>&nbsp;Hi' <?php echo $userRow['userEmail']; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav> 

	<div id="wrapper">

    	<div class="container">
        
        	<div class="page-header">
        	   <h3>Registration Form to Generate Code<?php ; ?></h3>
        	</div>
            
            <div class="row">
                <div class="col-lg-12">

                    <?php

                        if ( isset($codeMSG) ) {
                            ?>
                            <br/> <br/>
                            <div class="form-group">
                                <div class="alert alert-<?php echo ($codeTyp=="success") ? "success" : $codeTyp; ?>">
                                    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $codeMSG; ?>
                                </div>
                            </div>
                            <?php
                        }

                        if($errTyp == "success"){
                            ?>

                                <div class="form-group">
                                    <h4 class="">Random Code is: <b><?php echo $random; ?></b></h4>
                                </div>

                                <div class="form-group">
                                    <hr />
                                </div>

                                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

                                    <div class="col-lg-12">
                                        <div class="form-group ">
                                            <div class="col-lg-3">
                                                <div class="input-group mailuser-sect">
                                                    <label>
                                                        Want to email this code? 
                                                        <input name="check_list" class="form-control" value="true" type="checkbox" style="width: auto;position: relative;top: -10px;margin-right: 13px;">
                                                    </label>                
                                                    <input type="hidden" name="useremail" class="form-control" value="<?php echo $useremail; ?>" />
                                                    <input type="hidden" name="usercode" class="form-control" value="<?php echo $random; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <button type="submit" class="btn btn-block btn-primary" name="btn-email">Send</button>
                                            </div>
                                        </div>
                                    </div>

                                    <br/>

                                </form>                                

                            <?php
                        }
                    ?>

                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

                        <div class="form-group"> <hr /> </div>
                        
                        <?php
                            if ( isset($errMSG) ) {
                                ?>
                                <div class="form-group">
                                    <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                            
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" name="name" class="form-control" placeholder="Enter Name" maxlength="50" value="<?php echo $name ?>" />
                            </div>
                            <span class="text-danger"><?php echo $nameError; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                                <input type="email" name="email" class="form-control" placeholder="Enter Your Email" maxlength="40" value="<?php echo $email ?>" />
                            </div>
                            <span class="text-danger"><?php echo $emailError; ?></span>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                                <input type="tel" name="phone" class="form-control" placeholder="Enter Your Phone" value="<?php echo $phone ?>" />
                            </div>
                            <span class="text-danger"><?php echo $phoneError; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <hr />
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up</button>
                        </div>
                        
                    </form>

                </div>
            </div>
        
        </div>
    
    </div>
    
    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    
</body>
</html>
<?php ob_end_flush(); ?>