<?php

    ob_start();

    require_once 'dbconnect.php';

    $flag = 'false';

    if ( isset($_POST['usercode']) ) {
        
        $usercode = trim($_POST['usercode']);
        $usercode = strip_tags($usercode);
        $usercode = htmlspecialchars($usercode);

        if (empty($usercode)) {
            $error = 'true';
            $nameError = "Please enter your code.";
        } else{
            
            $checkcodeq =mysql_query("SELECT * FROM users WHERE userCode = '$usercode'");
            $queryresult = mysql_fetch_array($checkcodeq);

            if($queryresult){
                $query=mysql_query("SELECT * FROM users WHERE userCode = '$usercode' AND userStatus = 0");
                $userData = mysql_fetch_array($query);

                if(!empty($userData)){
                    $flag = 'true';
                }else{
                    $flag = 'already';
                }    
            }
            
        }
        echo $flag;
    }

?>