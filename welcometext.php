<?php
/*

This template is being downloaded from https://bootstrapious.com/admin-templates

*/
session_start();

$user = $_SESSION['user'];  // store session variable for user email

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
                <li class="active"> <a href="welcometext.php"><i class="fa fa-paste"></i>Upload Text</a></li>
                <li> <a href="welcomefile.php"> <i class="fa fa-file"></i>Upload File</a></li>
                <li> <a href="viewhistory.php"> <i class="fa fa-file"></i>View History</a></li>
              </ul>
            </nav>
            <div class="content-inner">
              <!-- Page Header-->
              <header class="page-header">
                <div class="container-fluid">
                  <h2 class="no-margin-bottom">Upload Text</h2>
                </div>
              </header>

              <!-- Forms Section-->
              <section class="forms" style="height: 1000px;"> 
                <div class="container-fluid">
                  <div class="row">
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
                          <h3 class="h4">Paste Your Text here</h3>
                        </div>
                        <div class="card-body">
                          <form class="form-horizontal" method="POST" action="createprocess.php">
                            <div class="form-group row">

                              <div class="col-sm-9">
                               <textarea class="form-control" name="textarea" style="margin-left: 150px" placeholder="Enter Your Text Here to check for plagiarism" rows="7" cols="70"></textarea>
                             </div>
                              <div class="col-sm-4 offset-sm-3">
                              <br>
                              <h6 align="center" style="color: red;"><?php echo@$_GET["wrong"]; ?></h6>
                             </div>
                           </div>
                           <div class="line"></div>
                           <div class="form-group row">
                            <label class="col-sm-3 form-control-label">Select Target Language</label>
                            <div class="col-sm-9 select">
                              <select name="plag" class="form-control">
                                <option value="en">English</option>
                                <option value="es">Spanish</option>
                                <option value="ru">Russian</option>
                                <option value="fr">French</option>
                                <option value="de">German</option>
                                <option value="zh">Chinese</option>
                                <option value="pt">Portuguese</option>
                             </select>
                            </div>
                          </div>
                          <div class="line"></div>
                          <div class="form-group row">
                            <div class="col-sm-4 offset-sm-3">
                              <button style="margin-left: 250px;" name="submit" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
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