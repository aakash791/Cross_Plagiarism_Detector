<?php
session_start();
header('Location: getresultsfile.php'); // redirect page to result page

$usid = $_SESSION['userid'];	// fetching userid from session variable
$file = $_POST['file'];			// fetching filename from uploaded file
$docid;		
$path = pathinfo($file);		// store path for file

if(isset($_POST['submit'])){

$count = 0;
if($path['extension'] == "pdf" || $path['extension'] == "txt" || $path['extension'] == "doc" || $path['extension'] == "docx")
	$count = 1;

if($count == 0)		// Show error message if no file is specified or file has a different format than pdf, txt, doc, docx
{
	header("Location: welcomefile.php?wrong=Invalid file extension or file not provided");
	exit();
}

     
        $servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "plagiarism";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		//Create sql query
 		$sql = "INSERT INTO documents (dname) VALUES (?)";
 		// prepare sql statement
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $dname);
  		
		$dname = $file;
		// execute query 
		$stmt->execute();

		$docid = $stmt->insert_id;

		$stmt->close();
		/* close connection */
   		
   		//Create sql query
		$sql = "INSERT INTO userdoc (docid, uid) VALUES  (?,?)";
  		 $stmt = $conn->prepare($sql);

		 $stmt->bind_param("ii", $did, $userid);

		 $did = $docid;
		 $userid = $usid;
		 // execute query 

		 //close connection
		 $stmt->execute();
		 $stmt->close();

 
 } 

$formlanguage = $_POST["plag"];  // fetching target language from dropdown menu
$formlanguage1;

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



$apiKey = 'AIzaSyB2cKr2KUqVHlojHZGMuwb7GboHk909Y6c';
$text; 

if($path['extension'] == "pdf")
{
	include('pdf2text.php');		// including downloaded code for converting pdf document into text
	$out = new PDF2Text();
	$out->setFilename($file);
	$out->decodePDF();
	$text = $out->output();
}
else if($path['extension'] == "docx" || $path['extension'] == "doc" )
{
	require('doc2txt.class.php');	// including downloaded code for converting doc/docx document into text
	$out = new Doc2Txt($file);
	$text = $out->convertToText();
}
else if($path['extension'] == "txt")
{
	$out = file_get_contents($file);	// fetching text from the text file 
	$text = $out;
}

  // *************Following PHP code is used from Google Translation API slightly modified to meet project requirements*******************//
    $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=en&target='.$formlanguage;
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handle);                 
    $responseDecoded = json_decode($response, true);
    curl_close($handle);

    $translated = $responseDecoded['data']['translations'][0]['translatedText'];
    
    if($formlanguage == "en") 		// if target language is english keep dont translate
    	$translated = $textstring;

// *************Following PHP code is used from Copyleaks API slightly modified to meet project requirements*******************
// https://api.copyleaks.com/documentation/sdks/php    	
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
	//All possible additional headers - only for CreateByFile \ CreateByOCR \ CreateByURL
	$additionalHeaders = array(
								//$clConst['SANDBOX_MODE_HEADER'], // Comment this line in production (leave sandbox mode)
								$clConst['ACCEPTED_LANGUAGE_HEADER'].': '.$formlanguage1,
								$clConst['PARTIAL_SCAN_HEADER']
								);

	//$urlToScan = "https://www.copyleaks.com";
	//echo '<Br/>Creating new scan-process (' . $urlToScan . ')...';
	//$process  = $clCloud->createByURL($urlToScan,$additionalHeaders);
	 $process  = $clCloud->createByText($translated, $additionalHeaders);
	//$process = $clCloud->createByFile($file,$additionalHeaders);
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
	
	$_SESSION['pid'] = $pid;
	$_SESSION['ctime'] = $ctime;
	$_SESSION['file'] = $file;
	$_SESSION['doc'] = $docid;
 	


	//create process by ID
	//$oldProcess = new CopyleaksProcess('4dede4d0-8e8d-464d-83cb-b9b6b3e028d4','27/09/2017 11:10:11',$clCloud->loginToken->authHeader(),$clConst['E_PRODUCT']['PUBLISHER']);

	//print_r($process->getStatus()); //get process status
	//print_r($oldProcess->getResult()); //get process results
	// print_r($oldProcess->getResult()); 
	// print_r($createFileProcess); //print createByFile response
	
	//DELETE process example
	// echo '<Br/>delete process';
	//$deleteProcess = $process->delete();
	// print_r($deleteProcess);
    //$plist = $oldProcess->getResult();
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