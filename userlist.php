<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	// if session is not set this will redirect to login page
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}
	// select loggedin users detail
	$res=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user']);
	$userRow=mysql_fetch_array($res);

   

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
          <a class="navbar-brand" href="/modul/home.php">Home</a>
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
        	   <h3>User List</h3>
        	</div>
            
            <div class="row">
                <div class="col-lg-12">

                    <div class="row">
                      <div class="col-lg-9">
                        
                      </div>
                      <div class="col-lg-3 generateResultData">
                        <a href="/modul/home.php" id="generateResul">Add New User</a>
                      </div>  
                    </div>


                    <div class="container">

                      <table class="table">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Code</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query=mysql_query("SELECT * FROM users WHERE userRole != 'admin'");
                                while ($userData = mysql_fetch_array($query)) {
                                   echo $tabledata = '<tr>
                                      <td>'.$userData['userName'].'</td>
                                      <td>'.$userData['userEmail'].'</td>
                                      <td>'.$userData['userPhone'].'</td>
                                      <td><b>'.$userData['userCode'].'<b></td>
                                      <td><a href="javascript:void(0)" data-attr_userCode="'.$userData['userCode'].'" class="sendCode">Send Code</a></td>
                                    </tr>';
                                }
                            ?> 
                        </tbody>
                      </table>
                    </div>

                </div>
            </div>
        
        </div>
    
    </div>
    
    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
    
</body>
</html>
<?php ob_end_flush(); ?>