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
                        <a href="/modul/generatecsv.php" id="generateResul">Generate CSV</a>
                      </div>  
                    </div>


                    <div class="resuldDatalist">

                      <?php
                      
                        $check=mysql_query("SELECT * FROM users WHERE userRole != 'admin' AND userStatus = 1");
                        $userRow=mysql_fetch_array($check);


                        if(!$userRow){
                          ?>
                            <br/><h4>No Data to show</h4>
                          <?php
                        }else{
                      ?>
                     
                      <table class="table resuldData">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Code</th>
                            <th>q1</th>
                            <th>q2</th>
                            <th>q3</th>
                            <th>q4</th>
                            <th>q5</th>
                            <th>q6</th>
                            <th>q7</th>
                            <th>q8</th>
                            <th>Total</th>
                            <th>Value Question</th>
                            <th>Date/Time of Submission</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                                $typeArray = array( 'Wisdom', 'Respect', 'Sustainability', 'Human Development', 'Value Question');

                                $query=mysql_query("SELECT * FROM users WHERE userRole != 'admin' AND userStatus = 1");
                                while ($userData = mysql_fetch_array($query)) {

                                    $userCode = $userData['userCode'];
                                    $res=mysql_query("SELECT userResult, submissiondate FROM userstestdata WHERE userCode='$userCode' ORDER by id DESC limit 1");
                                    $resultRow=mysql_fetch_array($res);

                                    $submissiondate =$resultRow['submissiondate'];
                                    $json_text = stripslashes($resultRow['userResult']);
                                    $formData = json_decode($json_text, true);

                                    $resultData = '';
                                    $valueData = '<td align="center" class="valueData">-</td>';
                                    $total = 0;
                                    if(!empty($formData)){

                                      foreach ($typeArray as $typekey => $typevalue) {

                                          foreach ($formData as $key => $value) {
                                            
                                            if($typevalue == $value['type']){

                                              if($typevalue == 'Value Question'){

                                                $valueData = '<td align="center" class="valueData">
                                                    <p>'. str_replace("aswqaswa","&",$value['commentsFirst']) .'</p>
                                                </td>';

                                              }else{

                                                $total += $value['First_answer'];
                                                $total += $value['Sec_answer'];

                                                $resultData .= '<td align="center">'.$value['First_answer'].'</td>
                                                    <td align="center">'.$value['Sec_answer'].'</td>';  

                                              }
                                              
                                            }
                                            
                                          }

                                      }
                                      
                                    }else{
                                      $resultData .= '<td align="center">-</td><td align="center">-</td><td align="center">-</td><td align="center">-</td><td align="center">-</td><td align="center">-</td><td align="center">-</td><td align="center">-</td>'; 
                                    }


                                   echo $tabledata = '<tr>
                                      <td>'.$userData['userName'].'</td>
                                      <td>'.$userData['userEmail'].'</td>
                                      <td>'.$userData['userPhone'].'</td>
                                      <td><b>'.$userData['userCode'].'<b></td>
                                      '.$resultData.'
                                      <td><b>'.$total.'<b></td>
                                      '.$valueData.'
                                      <td>'.$submissiondate.'</td>
                                    </tr>';
                                }


                            ?> 
                        </tbody>
                      </table>
                      <?php } ?>
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