<?php

session_start();

$formlanguage = $_POST["plag"];  // fetching target language from dropdown menu
$formlanguage1;
$textstring = $_POST["textarea"]; // fetching text from textbox


if(strlen($textstring) < 50)   // if length of text is less then 50 characters, display error message 
{
	header("Location: welcometext.php?wrong=Please provide provide text with more than 50 characters");
	exit();
}
if($formlanguage == "es")		// Spanish
	$formlanguage1 = "spa";
else if($formlanguage == "ru")	// Russian
	$formlanguage1 = "rus";
else if($formlanguage == "fr")	// French
	$formlanguage1 = "fra";
else if($formlanguage == "de")	// German
	$formlanguage1 = "deu";
else if($formlanguage == "zh")	// Chinese
	$formlanguage1 = "zho";
else if($formlanguage == "pt")	// Portuguese
	$formlanguage1 = "por";

// *************Following PHP code is used from Google Translation API slightly modified to meet project requirements*******************//

    $apiKey = '';
    $text = $textstring;
    $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=en&target='.$formlanguage;

    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handle);                 
    $responseDecoded = json_decode($response, true);
    curl_close($handle);

    $translated = $responseDecoded['data']['translations'][0]['translatedText'];

    if($formlanguage == "en")		// if target language is english keep dont translate
    	$translated = $textstring;

echo $translated;


// *************Following PHP code is used from Copyleaks API slightly modified to meet project requirements*******************
//https://api.copyleaks.com/documentation/sdks/php
$email = '';
$apiKey = '';

//dependencies and autoload
include_once( getcwd().'/autoload.php');
use Copyleaks\CopyleaksCloud;
use Copyleaks\CopyleaksProcess;

/* CREATE CONFIG INSTANCE */
$config = new \ReflectionClass('Copyleaks\Config');
$clConst = $config->getConstants();

/* 
	CONSTRUCT ACCEPTS 1 PARAMETER (type of product).

	ACCEPTED TYPES: 
	1. publisher 
	2. academy

	CONFIG HAS CONSTANTS FOR ACCEPTED TYPES:
	1. $clConst['E_PRODUCT']['PUBLISHER']
	2. $clConst['E_PRODUCT']['ACADEMY']

	DEFAULT:
	publisher
*/
$clCloud = new CopyleaksCloud($clConst['E_PRODUCT']['PUBLISHER']);

//LOGIN
try{
	$response = $clCloud->login($email, $apiKey);	
}catch(Exception $e){
	echo "<Br/>Caught exception: ". $e->getMessage();
	die();
}


//validate login token
if(!isset($clCloud->loginToken) || !$clCloud->loginToken->validate()){ 
	echo "<Br/>FALSE LOGIN CREDS";
	die();
}

$plist=array();
$token = $clCloud->loginToken->token; //get login token
$creditBalance = $clCloud->getCreditBalance(); //get credit balance

try{
	$additionalHeaders = array(
								//$clConst['SANDBOX_MODE_HEADER'], // Comment this line in production (leave sandbox mode)
								$clConst['ACCEPTED_LANGUAGE_HEADER'].': '.$formlanguage1,
								$clConst['PARTIAL_SCAN_HEADER']
								);

	//$urlToScan = "https://www.copyleaks.com";
	//echo '<Br/>Creating new scan-process (' . $urlToScan . ')...';
	//$process  = $clCloud->createByURL($urlToScan,$additionalHeaders);
	 $process  = $clCloud->createByText($translated, $additionalHeaders);
	// $process = $clCloud->createByFile('./tests/test.txt',$additionalHeaders);
	// $process  = $clCloud->createByOCR('./tests/c2253306-637a-44c3-8fe0-e0b5d237da32.jpg','English',$additionalHeaders);
	
	// print_r($process);
	
	/*
		create process from create file\ocr response
		
		Parameters:
		1. processID
		2. Creation time (UTC)
		3. Login header
		4. type of product (publisher\academy)

	*/
	
 	 $process = new CopyleaksProcess($process['response']['ProcessId'],
		$process['response']['CreationTimeUTC'],
		$clCloud->loginToken->authHeader(),
		$clConst['E_PRODUCT']['PUBLISHER']);

	echo "<BR/> Process created! (PID = '" . $process->processId . "')"; 

	$pid = $process->processId;
	$ctime = $process->creationTime;
	
	$_SESSION['pid'] = $pid;		// storing session value for process id
	$_SESSION['ctime'] = $ctime;	// storing session value process creation time

	header('Location: getresults.php'); // redirect page to result page
	
	//get processes list
	$plist = $clCloud->getProcessList();
	// print_r($plist);
	
	//get OCR's supported languages
	$ocrSupportedLanguages = $clCloud->getOCRLanguages();
	// print_r($ocrSupportedLanguages);
	
}catch(Exception $e){

	echo "<br/>Caught exception: ". $e->getMessage();
}

//build table from PHP array
function build_table($array){
    // start table
    $html = '<table>';
    // header row
    $html .= '<tr>';
    foreach($array[0] as $key=>$value){
            $html .= '<th>' . $key . '</th>';
        }
    $html .= '</tr>';

    // data rows
    foreach( $array as $key=>$value){
        $html .= '<tr>';
        foreach($value as $key2=>$value2){
			if($key2 == "URL" || $key2 == "Percents")
			{
				$value2 = is_array($value2) ? json_encode($value2) : $value2;
				$html .= '<td>' . @$value2 . '</td>';
			}
        	
        }
        $html .= '</tr>';
    }

    // finish table and return it

    $html .= '</table>';
    return $html;
}

//print process list as HTML table
if(isset($plist,$plist['response']) && count($plist['response'])>0)
	echo build_table($plist['response']);

?>