<?php 
   include("includes/authenticate.php");
   include("includes/dbConnector.php");
   include("includes/errorChecker.php");
   include("includes/create.php");
   
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
 
   $errorMessage = ""; 
   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
      $goodEntry = "Good Entry";
      //error check
      $evaluation = errorCheck($_POST['phone_name'], 'phone_name', $goodEntry);
      if ($evaluation === $goodEntry)
      {
         $statement = $db->query("SELECT account_id, id FROM user WHERE username = '".$_POST['user']."'");
         $phoneUser = $statement->fetch(PDO::FETCH_ASSOC);
         // submit to db
         $phoneData = array(
            'name'   => $_POST['phone_name'],
            'account_id'   => $phoneUser['account_id'],
            'user_id' => $phoneUser['id']
         );
         createPhone($db, $phoneData);
         header('Location: ./accountSummary.php');
         die('PHONE CREATED SUCCESSFUL');
      }
      else
      {
         $errorMessage = $evaluation;
      }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CellSpan - Add Phone</title>
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
         <a href='./accountSummary.php' type='button' class='btn btn-primary'><span class='glyphicon glyphicon-arrow-left'></span> Back to Account</a>
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
      <h1 style='text-align:center;'>Add Phone to Account</h1>
   </div>
</div>
<div class='row'>
<div class='col-sm-3'>
</div>
<div class='col-sm-6 content-container'>
   <form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='POST' role='form'>
      <div class='input-group input-group-lg'>
         <label>Phone Name</label>
         <input type='text' class='form-control' name='phone_name' placeholder='Phone Name'/>
         <?php echo "<h4 style='color:red'>$errorMessage</h4>";?>
      </div>
      <div class='input-group input-group-lg'>
         <label>This phone's user is:</label>
         <select name='user' class='form-control'>
         <?php
         foreach($db->query("SELECT * FROM user WHERE account_id = ".$user['account_id']) as $accountUser)
         {
            echo "<option value='".$accountUser['username']."'>".$accountUser['first_name']."</option>";
         }
         ?>
         </select>
      </div>
      <hr class='divider'/>
      <div style='text-align:center;'>
         <input type='submit' class='btn btn-primary btn-lg' value='Add Phone'/>
      </div>
      <br/>
   </form>
</div>
<div class='col-sm-3'>
</div>
</div>
</div>

</body>
</html>