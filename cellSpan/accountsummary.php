<?php 
   require("dbConnector.php");
   require("authenticate.php");
   
   $forwardTo = "login.php";
   
   try
   {
      session_start();
      
      // Check for valid user
      if(verify($_SESSION, 'user'))
      {
         $username = $_SESSION['user'];
         $db = loadDatabase();
      }
      else
      {
         kill($forwardTo);
      }
      
      // Fetch User Information
      $statement = $db->query("SELECT * FROM user WHERE username = '".$username."'");
      $user = $statement->fetch(PDO::FETCH_ASSOC);
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
            <a class="btn btn-info btn-md" href="./accountsettings.php">Account Settings <span class="glyphicon glyphicon-cog"></span></a>
            <a class="btn btn-info btn-md" href="./logout.php">Log Out <span class="glyphicon glyphicon-log-out"></span></a>
         </div>
      </div>
      <div class="col-sm-12">
         <h1 style="text-align:center;">Hello <?php echo $user['first_name']; ?></h1>
      </div>
   </div>
   
   <?php
      $username = $user['username'];
      foreach($db->query("SELECT p.id, p.name, p.connection FROM user u JOIN phone p ON p.account_id = u.account_id WHERE u.username = \"".$username."\"") as $phone)
      {
         echo "<div class='row'>";
         echo "<div class='col-sm-3 content-container'>\n";
         echo "<h2>".$phone['name']." <br/><a href='#'><span title='Phone Settings' class='glyphicon glyphicon-cog'></span></a> ";
         if ($phone['connection'] == 1)
         {
            echo "<span title='Phone is Connected' class='glyphicon glyphicon-ok' style='color:green'/>";
         }
         else
         {
            echo "<span title='Phone is disconnected' class='glyphicon glyphicon-remove' style='color:red'/> ";
         }
         echo " <a href='./addlocation.php?id=".$phone['id']."'><span title='Add Location' class='glyphicon glyphicon-plus-sign' style='color:green'/></a>";
         echo " <a href='./deletephone.php?id=".$phone['id']."'><span title='Delete this phone' class='glyphicon glyphicon-ban-circle' style='color:red'/></a>";
         echo "</h2>";
         $phoneLocQ = $db->query("SELECT l.latitude, l.longitude, l.altitude FROM phone p JOIN locationhistory l ON p.id = l.phone_id WHERE p.id=".$phone['id']);
         $location = $phoneLocQ->fetch(PDO::FETCH_ASSOC);
         if($location)
         {
            echo "Latitude: <strong>".$location['latitude']."</strong><br/>";
            echo "Longitude: <strong>".$location['longitude']."</strong><br/>";
            echo "Altitude: <strong>".$location['altitude']." ft</strong><br/>";
         }
         echo "</div>";
         echo "<div class='col-sm-8 content-container'>\n";
         if($location)
         {
            echo "<iframe id='map' width='100%' height='500' frameborder='0' style='border:0' src='https://www.google.com/maps/embed/v1/search?key=AIzaSyB-NY8Tr6mZJB9Wr_c2qlBptlAYF3vzx8o&q=".$location['latitude'].",".$location['longitude']."'></iframe>";
         }
         else
         {
            echo "<h1>No Location Available</h1>";
         }
         echo "</div>";
         echo "</div>";
      }
   ?>
   <div style="text-align:center;">
      <a href="./addphone.php" class="btn btn-primary btn-lg">Add Phone <span class="glyphicon glyphicon-phone"></span></a>
   </div>
</div>
</body>
</html>