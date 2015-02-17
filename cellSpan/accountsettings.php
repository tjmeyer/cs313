<?php 

   function kill()
   {
      header("Location: ./login.php");
      die();
   }
   
   require("dbConnector.php");
   try
   {
      $db = loadDatabase();
      session_start();
      
      // Check for valid user
     
      if (isset($_COOKIE['user']))
      {
         $username = $_COOKIE['user'];
      }
      else
      {
         kill();
      }
      $password = $_SESSION['pass'];
      
      $statement = $db->query("SELECT username, password FROM user WHERE username = '".$username."'");
      
      if ($row = $statement->fetch(PDO::FETCH_ASSOC))
      {
         if ($row['password'] !== $password)
         {
            kill();
         }
      }
      else
      {
         kill();
      }
      
      // Fetch Information
      $statement = $db->query("SELECT * FROM user WHERE username = '".$username."'");
      $user = $statement->fetch(PDO::FETCH_ASSOC);
      $account_id = $user['account_id'];
   }
   catch (PDOException $e)
   {
      echo "DATABASE ERROR: ".$e->getMessage();
      die();
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CellSpan - Create Account</title>
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
   <div class="row content-container">
      <div class="row">
         <div class="col-sm-12">
         <br/>
            <a class="btn btn-info btn-md" href="./accountsummary.php"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
            <a class="btn btn-info btn-md" href="./logout.php">Log Out <span class="glyphicon glyphicon-log-out"></span></a>
         </div>
      </div>
      <div class="col-sm-12">
         <h1 style="text-align:center;"><?php echo $user['first_name']; ?>'s Account Settings</h1>
      </div>
   </div>
   <div class="row">
      <div style="text-align:center;" class="col-sm-5 content-container">
         <h1>Users on Account</h1>
         <hr/>
         <?php
         foreach($db->query("SELECT * FROM user WHERE account_id = ".$account_id) as $row)
         {
            echo "<h3>".$row['first_name']." ".$row['last_name'].": ".$row['username']."</h3>";
            echo "<br/>";
         }
         ?>
         <hr/>
         <p><a href="#comingsoon" class="btn btn-primary btn-lg">Add User</a></p>
      </div>
      <div style="text-align:center;" class="col-sm-5 content-container">
         <h3>More Coming Soon</h3>
      </div>
   </div>
</div>
</body>
</html>