<?php

    ob_start();

    require_once 'dbconnect.php';
    include 'smtp-mail.php';

    $response = 'false';

    if ( isset($_POST['userCode']) ) {
        
        $usercode = trim($_POST['userCode']);
        $usercode = strip_tags($usercode);
        $usercode = htmlspecialchars($usercode);

        $query=mysql_query("SELECT userEmail FROM users WHERE userCode = '$usercode'");
        $userData = mysql_fetch_array($query);

        $from = 'admin@gmail.com';

        $to      = $userData['userEmail'];
        $subject = 'Thank You';
        $body = '<div>
                    <p>Hello,</p>
                    <p>You are registered for Scholarship Quiz. Please use below link and code to start the quiz:</p>

                    <p>Link: <a href="http://addon.testyou.in/modul/gentest.php">http://addon.testyou.in/modul/gentest.php</a></p>
                    <p><b>Code:</b>'.$usercode.'</p>

                    <p>Thanks</p>
                    <p>Online Test - Support Team</p>';

          $headers  = "From: <$sendto>\r\n";
          $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
          $headers .= "Reply-To: <$sendto>\r\n";
          

        $SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body);
        $SMTPChat = $SMTPMail->SendMail();
        $response = "true";

        echo $response;
    }

?>