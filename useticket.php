<?php

$allCodesFile = "./db/allCodesFile.txt";
$usedCodesFile = "./db/usedCodesFile.txt";
$dateTimeFormat = "Y-m-d H:i:s";

$allCodesContent = file_get_contents($allCodesFile);

$code = htmlspecialchars($_GET["ticket"]);

if (!empty($code) and strlen($code)==8) {
	$code = substr(trim($code), 0, 8);
	
	$pattern = preg_quote("{" . $code . "}", '/');
	$pattern = "/$pattern.*\$/m";
	
	if(preg_match_all($pattern, $allCodesContent, $matches)){
		$lineParams = explode(",", $matches[0][0]);
		$codeId = $lineParams[1];
		$expirationDate = strtotime($lineParams[2]);
		
		if (time() > $expirationDate) {
			//expired codes
			print "{ \"result\": 1, \"message\": \"ticket expired\" }";
			die();
		}
		else {
			$usedCodesContent = file_get_contents($usedCodesFile);

			$pattern = preg_quote("{" . $codeId . "}", '/');
			$pattern = "/$pattern.*\$/m";

			if(preg_match_all($pattern, $usedCodesContent, $matches)){
				//used codes
				print "{ \"result\": 2, \"message\": \"ticket already used\" }";
				die();
			}
			else{
				//valid code
				$promoUsedLine = "\r\n" . "{" . $codeId . "}" . date($dateTimeFormat);
				file_put_contents($usedCodesFile, $promoUsedLine, FILE_APPEND);
				print "{ \"result\": 0, \"message\": \"ticket validated\" }"; 
			}			
		}
	}
	else{
		//invalid code: not found 
		print "{ \"result\": 3, message: \"ticket not found\" }";
		die();
	}
}
else {
	//parameter code not found
	print "{ \"result\": 4, \"message: \"missing parameter: ticket\" }";
	die();
}

?>
