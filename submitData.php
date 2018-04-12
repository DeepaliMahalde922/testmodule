<?php

    ob_start();

    require_once 'dbconnect.php';
    include 'smtp-mail.php';

    if ( isset($_POST['data']) ) {

        $bundledagainJson = $_REQUEST['data'];
        $userCode = $_REQUEST['userCode'];

        $bundledagainJson = addslashes($bundledagainJson);
        // $forData = json_decode($json_text, true);

        $response = "false";
        if($userCode){
            
            $query = "INSERT INTO userstestdata(userCode, testdata, userResult) VALUES('$userCode','$bundledagainJson', 'null')";
            $res = mysql_query($query);   
            if ($res) {

                $response = "true";

                $update_query = "UPDATE users SET userStatus = 1 WHERE userCode = '$userCode'";
                $resquery = mysql_query($update_query);

                $response = "true";

                /*Fetch Answer data*/
                $ansres=mysql_query("SELECT * FROM userstestdata WHERE userCode='answer'");
                $answerDat=mysql_fetch_array($ansres);

                $json_text = stripslashes($answerDat['testdata']);
                $answerData = json_decode($json_text, true);

                /*Fetch User data*/

                $res1=mysql_query("SELECT * FROM userstestdata WHERE userCode='$userCode' ORDER by id DESC limit 1");
                $userRow=mysql_fetch_array($res1);

                //$json_text = stripslashes($userRow['testdata']);
                //$ddd = preg_replace( "/\r|\n/", "", $json_text );
                $formData = json_decode($userRow['testdata'], true);
                $tempdata = $formData;

                if(!empty($formData)){
                    foreach ($formData as $key => $value) {

                        foreach ($answerData as $anskey => $ansvalue) {

                            if( $ansvalue['type'] == $value['type'] ){
                                
                                if( $ansvalue['questionFirst'] == $value['questionFirst'] ){
                                    $tempdata[$key]['First_answer'] = 10;
                                }else{
                                    $tempdata[$key]['First_answer'] = 0;
                                }
                                if( $ansvalue['questionSec'] == $value['questionSec'] ){
                                    $tempdata[$key]['Sec_answer'] = 10;
                                }else{
                                    $tempdata[$key]['Sec_answer'] = 0;
                                }
                            }
                        }
                    }
                }

                $result = json_encode($tempdata);
                $result = addslashes($result);

                $update_query = "UPDATE userstestdata SET userResult = '$result' WHERE userCode = '$userCode'";
                $resquery = mysql_query($update_query);



            }
        }
        echo $response;
    }

?>