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
      $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
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
   }
   catch (PDOException $e)
   {
      echo "DATABASE ERROR: ".$e->getMessage();
      die();
   }
   
   if ($_SERVER["REQUEST_METHOD"] == "GET")
{
   $id = $_GET['id'];
   setcookie('phone_id', $id);
}
else
{
   if(isset($_COOKIE['phone_id']))
   {
      $id = $_COOKIE['phone_id'];
   }
}

$valid = FALSE;
foreach($db->query("SELECT p.id FROM user u JOIN phone p ON u.account_id = p.account_id WHERE username = '".$username."'") as $row)
{
   if ($row['id'] === $id)
   {
      $valid = true;
   }
}
if($valid)
{
   // Gather phone information
   $statement = $db->query("SELECT * FROM phone WHERE id =".$id);
   if($row = $statement->fetch(PDO::FETCH_ASSOC))
   {
      $name = $row['name'];
   }
   
   $lat = $lon = $alt = "";
   $latErr = $lonErr = $altErr = "";
   $goodEntry = "<span style='color:green;' class='glyphicon glyphicon-ok'></span>";
}
else
{
   echo "<h1 class='content-container'>You are not authorized to edit this entry</h1>";
   die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CellSpan - Add Location</title>
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

<?php


   
if($id != null && $_SERVER["REQUEST_METHOD"] == "POST")
{
   if (empty($_POST['lat']))
   {
      $latErr = "huh?";
   }
   else
   {
      $lat = $_POST['lat'];
      if (!is_numeric($lat))
      {
         $latErr = "Can only contain numbers.";
      }
      else
      {
         $lat = $_POST['lat'];
         $latErr = $goodEntry ;
      }
   }
   if (empty($_POST['lon']))
   {
      $lonErr = "huh?";
   }
   else
   {
      $lon = $_POST['lon'];
      if (!is_numeric($lon))
      {
         $lonErr = "Can only contain numbers.";
      }
      else
      {
         $lon = $_POST['lon'];
         $lonErr = $goodEntry ;
      }
   } 
   if (empty($_POST['alt']))
   {
      $altErr = "huh?";
   }
   else
   {
      $alt = $_POST['alt'];
      if (!is_numeric($alt))
      {
         $altErr = "Can only contain numbers.";
      }
      else
      {
         $alt = $_POST['alt'];
         $altErr = $goodEntry ;
      }
   }
   if ($latErr == $goodEntry &&
         $altErr == $goodEntry &&
         $lonErr == $goodEntry)
   {
      //perform sql here
      $time = date('Y-m-d G:i:s');
      $query = "INSERT INTO locationhistory(latitude, longitude, altitude, time_of_history, phone_id) VALUES(:lat, :lon, :alt, :time, :phone_id)";
      $statement = $db->prepare($query);
      $statement->bindParam(':lat', $lat);
      $statement->bindParam(':lon', $lon);
      $statement->bindParam(':alt', $alt);
      $statement->bindParam(':time', $time);
      $statement->bindParam(':phone_id', $id);
      
      $statement->execute();
      
      if (isset($_COOKIE['phone_id']))
      {
         unset($_COOKIE['phone_id']);
      }
      header("Location: ./accountsummary.php");
      die();
   }

}

?>
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
         <h1 style="text-align:center;">Add location to <?php echo $name; ?>?</h1>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-4">
      </div>
      <div class="col-sm-4 content-container">
         <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
         <br/>
         <label>Latitude</label>
         <input class="form-control input-lg" type="text" name="lat" maxlength="9" size="7" value="<?php echo $lat;?>"/>
         <?php echo "<h4 style='color:red'>$latErr</h4>";?>
         <br/>
         <label>Longitude</label>
         <input class="form-control input-lg" type="text" name="lon" maxlength="9" size="7" value="<?php echo $lon;?>"/>
         <?php echo "<h4 style='color:red'>$lonErr</h4>";?>
         <br/>
         <label>Altitude</label>
         <input class="form-control input-lg" type="text" name="alt" maxlength="5" size="5" value="<?php echo $alt;?>"/>
         <?php echo "<h4 style='color:red'>$altErr</h4>";?>
         <br/>
         <div class="row" style="text-align:center;">
            <br/>
            <p><input type="submit" class="btn btn-primary btn-lg"/></p>
         </div>
      </div>
      <div class="col-sm-4">
      </div>
      </form>
   </div>
</div>

</body>
</html>