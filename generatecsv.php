<?php


	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="result.csv"');
	 
	// do not cache the file
	header('Pragma: no-cache');
	header('Expires: 0');
	 
	// create a file pointer connected to the output stream
	$file = fopen('php://output', 'w');
	 
	// send the column headers
	fputcsv($file, array('Name', 'Email', 'Phone', 'Code', 'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'total', 'Value Question'));
	 
	// Sample data. This can be fetched from mysql too

	$data = array();

	require_once 'dbconnect.php';

	$typeArray = array( 'Wisdom', 'Respect', 'Sustainability', 'Human Development', 'Value Question');

	$query=mysql_query("SELECT * FROM users WHERE userRole != 'admin' AND userStatus = 1");
	while ($userData = mysql_fetch_array($query)) {

	    $userCode = $userData['userCode'];
	    $res=mysql_query("SELECT userResult FROM userstestdata WHERE userCode='$userCode'");
	    $resultRow=mysql_fetch_array($res);

	    $json_text = stripslashes($resultRow['userResult']);
	    $formData = json_decode($json_text, true);

	    $resultData = '';
	    $total = 0;

	    if(!empty($formData)){
	    	$marksdata = array();
			foreach ($typeArray as $typekey => $typevalue) {
				foreach ($formData as $key => $value) {
					if($typevalue == $value['type']){
						if($typevalue == 'Value Question'){
							$valueData = $value['commentsFirst'];
						}else{
							$total += $value['First_answer'];
							$total += $value['Sec_answer'];
							$marksdata[$value['type']][] = $value['First_answer'];
							$marksdata[$value['type']][] = $value['Sec_answer'];
						}
					}
				}
			}
	  		$dataarr = array(
	  			$userData['userName'], 
	  			$userData['userEmail'], 
	  			$userData['userPhone'], 
	  			$userData['userCode'], 
	  			$marksdata['Wisdom'][0], 
	  			$marksdata['Wisdom'][1], 
	  			$marksdata['Respect'][0], 
	  			$marksdata['Respect'][1], 
	  			$marksdata['Sustainability'][0], 
	  			$marksdata['Sustainability'][1], 
	  			$marksdata['Human Development'][0],
	  			$marksdata['Human Development'][1],
	  			$total,
	  			$valueData
	  		);
	  		fputcsv($file, $dataarr);
	    }
	}
	exit();
	ob_get_clean();
	
?>