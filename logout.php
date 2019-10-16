<?php
   session_start();
   // destroy session and redirect to index page
   if(session_destroy()) {
      header("Location: index.php?success=Logout Successful");
   }
?>