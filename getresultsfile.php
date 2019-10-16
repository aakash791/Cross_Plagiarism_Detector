<?php
/*

The html template is being downloaded from https://bootstrapious.com/admin-templates

*/
session_start();

$user = $_SESSION['user'];    // store session value for user email
$pid = $_SESSION['pid'];      // store session value for process id
$ctime = $_SESSION['ctime'];  // store seesion value for process creation time
$docid = $_SESSION['doc'];    // store session value for docid


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

	$additionalHeaders = array(
								//$clConst['SANDBOX_MODE_HEADER'], // Comment this line in production (leave sandbox mode)
								$clConst['PARTIAL_SCAN_HEADER']
								);
	
	/*
		create process from create file\ocr response
		
		Parameters:
		1. processID
		2. Creation time (UTC)
		3. Login header
		4. type of product (publisher\academy)

	*/

 	//create process by ID
   $oldProcess = new CopyleaksProcess($pid,$ctime,$clCloud->loginToken->authHeader(),$clConst['E_PRODUCT']['PUBLISHER']);

	//DELETE process example
	// echo '<Br/>delete process';
	//$deleteProcess = $process->delete();
	// print_r($deleteProcess);
   $plist = $oldProcess->getResult();
	//get processes list
	//$plist = $clCloud->getProcessList();
	// print_r($plist);
  // $array = $plist['response'];


 }catch(Exception $e){

   echo "<br/>Caught exception: ". $e->getMessage();
 }

//build table from PHP array
 function build_table($array,$docid){
    // start table
  $html = '<div class="card-body">
  <table class="table table-striped table-hover">';
    // header row
    $html .= '<thead>
    <tr>';
      $html .= '<th>' . "#" . '</th>';
      foreach($array[0] as $key=>$value){

        if($key == "URL" )
        {
          $html .= '<th>' . "Source URL" . '</th>';
        }
        if( $key == "Percents" )
        {
          $html .= '<th>' . "Plagiarism %" . '</th>';
        }
        if( $key == "NumberOfCopiedWords" )
        {
          $html .= '<th>' . "Copied Words" . '</th>';
        }
        if( $key == "EmbededComparison")
        {
          $html .= '<th>' . "Detailed Report" . '</th>';
        }
      }
      $html .= ' </tr>
    </thead>';

    // data rows
    $var=1;
    foreach( $array as $key=>$value){
      $html .= '<tbody>
      <tr>';
        $html .=  '<td>' . $var . '</td>';                  
        foreach($value as $key2=>$value2){

          if($key2 == "URL" || $key2 == "Percents" || $key2 == "NumberOfCopiedWords")
          {
            $value2 = is_array($value2) ? json_encode($value2) : $value2;
            $html .= '<td>' . @$value2 . '</td>';
          }
          if($key2 == "EmbededComparison")
          {
            $value2 = is_array($value2) ? json_encode($value2) : $value2;
            $html .= '<td><a href=' . @$value2 . ' target=_blank>click</a></td>';
          }
        }
        $html .= '</tr>';
        $var++;
      }



      $html .= '</tbody>
    </table>
  </div>';

    // finish table and return it

  $html .= '</table>';

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "plagiarism";

     // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  $sql = "INSERT INTO userdocresults (docid, url, percent, link, words ) VALUES ( ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);

  $stmt->bind_param("isdsi", $did, $url, $percent, $link, $words);

  $did = $docid;

  foreach( $array as $key=>$value){

   foreach($value as $key2=>$value2)
   {
    if($key2 == "URL" )
    {
      $value2 = is_array($value2) ? json_encode($value2) : $value2; 
      $url = $value2; 
    }
    if($key2 == "Percents" )
    {
      $value2 = is_array($value2) ? json_encode($value2) : $value2;  
      $percent = $value2; 
    }
    if( $key2 == "NumberOfCopiedWords" )
    {
      $value2 = is_array($value2) ? json_encode($value2) : $value2;
      $words = $value2;   
    }
    if( $key2 == "EmbededComparison")
    {
      $value2 = is_array($value2) ? json_encode($value2) : $value2;
      $link = $value2;   
    }  
  }

  $stmt->execute(); 

}


$stmt->close();




return $html;
}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cross Language Plagiarism Detection Tool</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="all,follow">
  <!-- Bootstrap CSS-->
  <link rel="stylesheet" href="css/css/bootstrap.min.css">
  <!-- Google fonts - Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
  <!-- theme stylesheet-->
  <link rel="stylesheet" href="css/css/style.default.css" id="theme-stylesheet">
  <!-- Custom stylesheet - for your changes-->
  <link rel="stylesheet" href="css/css/custom.css">
  <!-- Favicon-->
  <link rel="shortcut icon" href="img/favicon.ico">
  <!-- Font Awesome CDN-->
  <!-- you can replace it by local Font Awesome-->
  <script src="https://use.fontawesome.com/99347ac47f.js"></script>
  <!-- Font Icons CSS-->
  <link rel="stylesheet" href="https://file.myfontastic.com/da58YPMQ7U5HY8Rb6UxkNf/icons.css">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
      </head>
      <body>
        <div class="page form-page">
          <!-- Main Navbar-->
          <header class="header">
            <nav class="navbar">
              <!-- Search Box-->

              <div class="container-fluid">
                <div class="navbar-holder d-flex align-items-center justify-content-between">
                  <!-- Navbar Header-->
                  <div class="navbar-header">
                    <!-- Navbar Brand --><a href="dashboard.php" class="navbar-brand">
                    <div class="brand-text brand-big hidden-lg-down"><span>Cross-Language</span><strong>Plagiarism Detection</strong></div>
                    <div class="brand-text brand-small"><strong>CLPD</strong></div></a>
                    <!-- Toggle Button--><a id="toggle-btn" href="#" class="menu-btn active"><span></span><span></span><span></span></a>
                  </div>
                  <!-- Navbar Menu -->
                  <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                    <!-- Search-->

                    <!-- Notifications-->

                    <!-- Messages                        -->

                    <!-- Logout    -->
                    <li class="nav-item"><a href="logout.php" class="nav-link logout">Logout<i class="fa fa-sign-out"></i></a></li>
                  </ul>
                </div>
              </div>
            </nav>
          </header>
          <div class="page-content d-flex align-items-stretch"> 
            <!-- Side Navbar -->
            <nav class="side-navbar">
              <!-- Sidebar Header-->
              <div class="sidebar-header d-flex align-items-center">
                <div class="avatar"><img src="img/web-user.jpg" alt="..." class="img-fluid rounded-circle"></div>
                <div class="title">
                  <h1 class="h6"><?php echo $user; ?></h1>
                  <p>Logged In</p>
                </div>
              </div>
              <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
              <ul class="list-unstyled">
                <li> <a href="dashboard.php"> <i class="fa fa-address-book-o"></i>Show Dashboard</a></li>
                <li> <a href="welcometext.php"><i class="fa fa-paste"></i>Upload Text</a></li>
                <li class="active"> <a href="welcomefile.php"> <i class="fa fa-file"></i>Upload File</a></li>
                <li> <a href="viewhistory.php"> <i class="fa fa-file"></i>View History</a></li>
              </ul>
            </nav>
            <div class="content-inner">
              <!-- Page Header-->
              <header class="page-header">
                <div class="container-fluid">
                  <h2 class="no-margin-bottom">Upload File</h2>
                </div>
              </header>

              <!-- Forms Section-->
              <section class="forms" style="height: 1000px;"> 
                <div class="container-fluid">
                  <div class="row">
                    <!-- Basic Form-->

                    <!-- Horizontal Form-->

                    <!-- Inline Form-->

                    <!-- Modal Form-->

                    <!-- Form Elements -->
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-close">
                          <div class="dropdown">
                            <button type="button" id="closeCard" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-ellipsis-v"></i></button>
                            <div aria-labelledby="closeCard" class="dropdown-menu has-shadow"><a href="#" class="dropdown-item remove"> <i class="fa fa-times"></i>Close</a><a href="#" class="dropdown-item edit"> <i class="fa fa-gear"></i>Edit</a></div>
                          </div>
                        </div>
                        <div class="card-header d-flex align-items-center">
                          <h3 class="h4">Results</h3>
                        </div>
                        <div class="card-body">
                          <p><?php if(isset($plist,$plist['response']) && count($plist['response'])>0)
                          echo build_table($plist['response'],$docid);
                           else {echo "We’re searching the internet for similar text provided in the input. This may take some time, depending on the size of your input and other factors.";
                           echo "<script>
                           setTimeout(function() {
                            window.location.href = window.location; 
                          }, 5000);
                        </script>";} 
                        ?><p>
                      </div>
                    </div>
                  </div>
                <!-- </div>  -->
              </section>
              <!-- Page Footer-->
              <footer class="main-footer">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-sm-6">
                      <p>A-Team &copy; 2017-2019</p>
                    </div>
                    <div class="col-sm-6 text-right">
                      <p>Design by <a href="https://bootstrapious.com/admin-templates" class="external">Bootstrapious</a></p>
                      <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
                    </div>
                  </div>
                </div>
              </footer>
            </div>
          </div>
        </div>
        <!-- Javascript files-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="js/js/tether.min.js"></script>
        <script src="js/js/bootstrap.min.js"></script>
        <script src="js/js/jquery.cookie.js"> </script>
        <script src="js/js/jquery.validate.min.js"></script>
        <script src="js/js/front.js"></script>
        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
        <!---->
        <script>
          (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
          e=o.createElement(i);r=o.getElementsByTagName(i)[0];
          e.src='//www.google-analytics.com/analytics.js';
          r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
          ga('create','UA-XXXXX-X');ga('send','pageview');
        </script>
      </body>
      </html>