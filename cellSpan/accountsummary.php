<?php 
   include("includes/authenticate.php");
   include("includes/dbConnector.php");
   
   // Verify user session 
   $db = loadDatabase();
   session_start();
   if(!verify($_SESSION, 'user'))
   {
      // kick 'em out!
      header('Location: ./logout.php');
      die('INVALID SESSION');
   }
   
   // get user info
   $statement = $db->query("SELECT * FROM user WHERE username = '".$_SESSION['user']."'");
   $user = $statement->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CellSpan - <?php echo $user['first_name']."'s Account Summary";?></title>
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
   
   <script src="../js/main.js"></script>
   
</head>
<body>
<div class="container">
<div class="row">
   <div class='btn-group btn-group-justified' role='group'>
      <div class='btn-group' role='group'>
         <a href='./logout.php' type='button' class='btn btn-primary'><span class='glyphicon glyphicon-log-out'></span> Logout</a>
      </div>
      <?php 
      if($user['master_user'] == 1)
      {
         echo "<div class='btn-group' role='group'>
                  <a href='./accountSettings.php' type='button' class='btn btn-primary'><span class='glyphicon glyphicon-cog'></span> Account Settings</a>
               </div>";
      }
      ?>
   </div>
   <div class="col-sm-12 content-container">
      <h1 style='text-align:center;'><?php echo $user['first_name'];?>'s Account Summary</h1>

   </div>
</div>
<?php 
// Display phones on account
foreach($db->query('SELECT * FROM phone WHERE account_id = '.$user['account_id']) as $phone)
{
   echo "<div class='row content-container'>
         <div style='text-align:center;' class='col-sm-3'>
         <h2>".$phone['name']."</h2>
         <div class='row'><h2>";
   if($user['master_user'] == 1)
   {
      echo "<a href='./phoneSettings.php?id=".$phone['id']."'><span title='phone settings' class='glyphicon glyphicon-cog'/></a> ";
   }
   if ($phone['connection'] == 1)
   {
      echo "<span title='phone connected' style='color:green;' class='glyphicon glyphicon-ok'/>";
   }
   else
   {
      echo "<span title='phone disconnected' style='color:red;' class='glyphicon glyphicon-exclamation-sign'/>";
   }
   echo "</h2></div>
         </div>
         <div class='col-sm-9'>";
            // Get current location for display
   $statement = $db->query("SELECT latitude, longitude FROM location WHERE phone_id = ".$phone['id']);
   $location  = $statement->fetch(PDO::FETCH_ASSOC);
   if($location)
   {
      echo "<iframe id='map' width='100%' height='500' frameborder='0' style='border:0' src='https://www.google.com/maps/embed/v1/search?key=AIzaSyB-NY8Tr6mZJB9Wr_c2qlBptlAYF3vzx8o&q=".$location['latitude'].",".$location['longitude']."'></iframe>";
   }
   else
   {
      echo "<h1>No Location Available</h1>";
   }
   echo "</div>
         </div>";
   
}
if($user['master_user'] == 1)
{
   echo "<div class='row' style='text-align:center'>
            <a href='./addPhone.php' class='btn btn-primary btn-lg'>Add Phone</a>
         </div>";
}
?>

</div>
</div>

</body>
</html>