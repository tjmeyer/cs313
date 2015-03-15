<?php 
   include("includes/authenticate.php");
   include("includes/dbConnector.php");
   include("includes/errorChecker.php");
   
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
   
   // verify user is master
   if($user['master_user'] != 1)
   {
      header('Location: ./logout.php');
      die('NOT MASTER USER, SESSION EXPIRED');
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CellSpan - Account Settings</title>
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
   <!--HEADER-->
   <div class="row">
      <div class='btn-group btn-group-justified' role='group'>
         <div class='btn-group' role='group'>
            <a href='./accountSummary.php' type='button' class='btn btn-primary'><span class='glyphicon glyphicon-arrow-left'></span> Back to Account</a>
         </div>
      </div>
      <div class="col-sm-12 content-container">
         <h1 style='text-align:center;'>Master Account Settings</h1>
      </div>
   </div><!--END HEADER-->
   <div class='col-sm-5 content-container' style='text-align:center'>
      <h2>Account Users</h2>
      <?php 
         foreach($db->query("SELECT * FROM user WHERE account_id = ".$user['account_id']) as $userRow)
         {
            if($userRow['master_user'] != 1)
            {
               echo "<a href='./userSettings.php?username=".$userRow['username']."'><h4>".$userRow['first_name']." ".$userRow['last_name']." (".$userRow['username'].")</h4></a>\n";
            }
            else
            {
               echo "<h4>".$userRow['first_name']." ".$userRow['last_name']." (".$userRow['username'].")</h4>\n";
            }
               // create and unordered list of phones for each user
            echo "<div class='row'>
                  <div class='col-sm-3'></div>
                  <div class='col-sm-4' style='text-align:left;'>";
            echo "<ul>\n";
            foreach($db->query("SELECT * FROM phone WHERE user_id = ".$userRow['id']) as $phoneRow)
            {
               echo "<li><a href='./phoneSettings.php?id=".$phoneRow['id']."'>".$phoneRow['name']."</a></li>\n";
            }
            echo "</ul></div></div>\n";
         }
      ?>
      <hr class='divider'/>
      <div class='row'>
         <a href='./addUser.php' class='btn btn-primary btn-lg'><span class='glyphicon glyphicon-plus' style='color:green;'></span> Add User to Account</a>
      </div>
      <br/>
   </div>
   <div class='col-sm-6 content-container'>
   <h2 style='text-align:center;'>User Information</h2>
      <form action="./changeName.php" method="POST">
         <label>First Name</label>
         <input type='text' name='first_name' class='form-control input-lg' value='<?php echo $user['first_name'];?>' placeholder='First Name'/>
         <label>Last Name</label>
         <input type='text' name='last_name' class='form-control input-lg' value='<?php echo $user['last_name'];?>' placeholder='Last Name'/>
         <hr class='divider'/>
         <div style='text-align:center;'>
            <input type='submit' value='Save' class='btn btn-primary btn-lg'/>
         </div>
         <br/>
      </form>
   </div>
</div>
</body>
</html>
