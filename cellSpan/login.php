<?php 
   require("dbConnector.php");
   try
   {
      $db = loadDatabase();
   }
   catch (PDOException $e)
   {
      echo "ERROR: ".$e->getMessage();
      die();
   }
$location = "cs313/cellSpan";
   ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CellSpan Login</title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   
   <!-- Main CSS Style sheet for general styling -->
   <link rel="stylesheet" href="../css/main.css">
   
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
   
   <script src="../js/main.js"></script>
   
</head>
<body>
<div class="container">
   <div class="row">
      <br/><br/><br/><!--Spacer-->
   </div>
   <div class="row">
      <div class="col-sm-4"></div>
      <div class="col-sm-4 content-container">
         <h2 style="text-align:center;">Welcome Back!</h2>
         <hr/>
         <form action="" name="loginForm">
            <p><input type="text" class="form-control input-lg" name="username" placeholder="username"/></p>
            <p><input type="password" class="form-control input-lg" name="user_password" placeholder="password"/></p>
            <p><div style="text-align:center;"><a type="submit" class="btn btn-primary btn-lg">Login <span class="glyphicon glyphicon-log-in"></span></a>
            <a href="./createaccount.php" class="btn btn-default btn-lg">New Account <span class="glyphicon glyphicon-plus"></span></a></div></p>
         </form>
      </div>
      <div class="col-sm-4"></div>
   </div>   
</div>
</body>
</html>