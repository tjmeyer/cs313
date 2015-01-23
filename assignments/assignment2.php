<?php
   $cookieName = "test";
   $cookieValue = 1;
   if(isset($_COOKIE[$cookieName]))
   {
      $_COOKIE[$cookieName]++;
   }
   else
   {
      setcookie($cookieName,$cookieValue,time()+10*60);
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Assignment 1</title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   
   <!-- Main CSS Style sheet for general styling -->
   <link rel="stylesheet" href="./css/main.css">
   
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
   
   <script src="./js/main.js"></script>
   
</head>
<body>
   <!-- Navigation Menu -->
   <nav class="navbar navbar-default">
      <div class="container-fluid">
         <div class="navbar-header">
            <a class="navbar-brand" href="#">Trevor Meyer</a>
         </div>
         <div>
            <ul class="nav navbar-nav">
               <li class="active"><a href="#">About Me</a></li>
               <li><a href="./assignments.html">Assignments</a></li>
               <li><a href="./contact.html">Contact</a></li>
               <li><a href="./fun.html">For Fun</a></li>
            </ul>
         </div>
      </div>
   </nav>
   <!-- End Navigation Menu -->
   
	<div class="container">
		
	</div>
</html>
