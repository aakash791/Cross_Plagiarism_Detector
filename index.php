
<!DOCTYPE html>
<html>
<!-- The html is template is downlaoded from https://bootstrapious.com/coming-soon-themes  -->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cross Language Plagiarism Detection Tool</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="all,follow">
  <!-- Bootstrap CSS-->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <!-- Font Awesome CSS-->
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <!-- Google fonts - Roboto for copy, Montserrat for headings-->
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,700">
  <!-- theme stylesheet-->
  <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
  <!-- Custom stylesheet - for your changes-->
  <link rel="stylesheet" href="css/custom.css">
  <!-- Favicon-->
  <link rel="shortcut icon" href="favicon.png">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
        <style type="text/css">
          .btn-sample { 
            color: #000000; 
            background-color: #FFFFFF; 
            border-color: #D9D7E0; 
          } 

          .btn-sample:hover, 
          .btn-sample:focus, 
          .btn-sample:active, 
          .btn-sample.active, 
          .open .dropdown-toggle.btn-sample { 
            color: #000000; 
            background-color: #F7F7F7; 
            border-color: #D9D7E0; 
          } 

          .btn-sample:active, 
          .btn-sample.active, 
          .open .dropdown-toggle.btn-sample { 
            background-image: none; 
          } 

          .btn-sample.disabled, 
          .btn-sample[disabled], 
          fieldset[disabled] .btn-sample, 
          .btn-sample.disabled:hover, 
          .btn-sample[disabled]:hover, 
          fieldset[disabled] .btn-sample:hover, 
          .btn-sample.disabled:focus, 
          .btn-sample[disabled]:focus, 
          fieldset[disabled] .btn-sample:focus, 
          .btn-sample.disabled:active, 
          .btn-sample[disabled]:active, 
          fieldset[disabled] .btn-sample:active, 
          .btn-sample.disabled.active, 
          .btn-sample[disabled].active, 
          fieldset[disabled] .btn-sample.active { 
            background-color: #FFFFFF; 
            border-color: #D9D7E0; 
          } 

          .btn-sample .badge { 
            color: #FFFFFF; 
            background-color: #000000; 
          }
       </style>
      </head>
      <body>
        <div class="container-fluid">
          <div class="row intro">
            <div class="col-md-6 col-sm-8 intro-left"> 
              <div class="intro-left-content">
                <p> <img src="img/logo1.png" alt="Template Logo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id='b1' class="btn btn-sample">Login</button></p>
                <br>
                <h6 align="center" style="color: red;"><?php echo@$_GET["success"]; ?></h6>

                <h1>Cross Language Plagiarism Detection Tool</h1>
                <p class="lead">Cross-Plagiarism detection tool allows you check for plagiarism in various langauges such as Russian, German, Chinese etc and give detailed report on copied contents</p>
                <p>There are two ways you can check for plagiarism either using file upload system or pasting file in a textbox area.</p>

                <p class="credit">&copy; 2017 A Team | Template by <a href="https://bootstrapious.com/coming-soon-themes" class="external">Bootstrapious</a>  </p>
                <!-- Please do not remove the backlink to bootstrapious unless you support us at http://bootstrapious.com/donate. It is part of the license conditions. Thanks for understanding :) -->
              </div>
            </div>
            <div style="background-image: url('img/pexels-photo-40120.jpg');" class="col-md-6 col-sm-4 intro-right background-gray"></div>
          </div>
        </div>
        <!-- Javascript files-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.cookie.js"> </script>
        <script src="js/front.js"></script>
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
        <script type="text/javascript">
          document.getElementById("b1").onclick = function () {
              location.href = "login.php";
          };
        </script>
      </body>
      </html>