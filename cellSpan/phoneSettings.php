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
   
   // get phone information
   if(isset($_GET['id']))
   {
      $statement = $db->query("SELECT * FROM phone WHERE id = ".$_GET['id']);
      $phone = $statement->fetch(PDO::FETCH_ASSOC);
   }
   else
   {
      die("No GET Information Received for this device");
   }
   
   // kick them back if they don't have permission to this phone
   if ($phone['account_id'] != $user['account_id'])
   {
      die("YOU DO NO HAVE PERMISSIONS TO EDIT THIS DEVICE");
   }
   
   // error checking
   $phone_name_error = "";
   $goodEntry = "<h4 style='color:green;'><span class='glyphicon glyphicon-ok'></span> Saved!</h4>";
   
   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
      if (empty($_POST['phone_name']))
      {
         $phone_name_error = "<h4 style='color:red;'><span class='glyphicon glyphicon-warning-sign'></span> Cannot be empty!</h4>";
      }
      else
      {
         if (!ctype_alnum($_POST['phone_name']))
         {
            $phone_name_error = "<h4 style='color:red;'><span class='glyphicon glyphicon-warning-sign'></span> Can only contain numbers and letters.</h4>";
         }
         else
         {
            $phone_name_error = $goodEntry;
            $phone['name'] = $_POST['phone_name'];
         }
      }
      
      if($phone_name_error == $goodEntry)
      {
         $query = "UPDATE phone SET name = :name, user_id = :id WHERE id = ".$phone['id'];
         $statement = $db->prepare($query);
         $statement->bindParam(':name', $_POST['phone_name']);
         $statement->bindParam(':id', $_POST['user']);
         $statement->execute();
      }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CellSpan - Phone Settings</title>
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
         <h1 style='text-align:center;'><?php echo $phone['name'];?>'s Settings and History</h1>
      </div>
   </div><!--END HEADER-->
   
   <!--NAME CHANGER-->
   <div class='row content-container'>
      <br/>
      <br/>
      <form method="POST" action="<?php echo htmlspecialchars('./phoneSettings.php?id='.$phone['id']);?>">
         <div class='row'>
            <div class='col-sm-1'></div>
            <div class='col-sm-7'>
               <div class="input-group input-group-lg">
                  <span class="input-group-addon" id="basic-addon1">Phone Name</span>
                  <input type="text" class="form-control" name="phone_name" placeholder='Phone Name' value='<?php echo $phone['name'];?>' aria-describedby="basic-addon1">
               </div>
            </div>
            <div class='col-sm-4'>
               <h4><?php echo $phone_name_error;?></h4>
            </div>
         </div>
         <br/>
         <div class='row'>
            <div class='col-sm-1'></div>
            <div class='col-sm-7'>
               <div class='input-group input-group-lg'>
                  <span class="input-group-addon" id="basic-addon2">Phone User</span>
                  <select name='user' class='form-control' aria-describedby="basic-addon2">
                  <?php
                  foreach($db->query("SELECT * FROM user WHERE account_id = ".$user['account_id']) as $accountUser)
                  {
                     echo "<option value='".$accountUser['id']."'";
                     if($phone['user_id'] == $accountUser['id'])
                     {
                        echo " selected='selected'";
                     }
                     echo ">".$accountUser['first_name']."</option>";
                  }
                  ?>
                  </select>
               </div>
            </div>
            <div class='col-sm-4'>
            </div>
         </div>
         <hr class='divider'/>
         <div class='row' style='text-align:center;'>
            <input type='submit' value='Save' class='btn btn-primary btn-lg'/>
         </div>
         <br/>
      </form>
   </div>
   <!--END NAME CHANGER-->
   
   <?php
   if($user['master_user'] == 1)
   {
   echo "<div style='text-align:center;' class='row'>
            <a href='./deletePhone.php?id=".$phone['id']."' class='btn btn-danger btn-lg'><span class='glyphicon glyphicon-trash'></span> Delete This Phone</a>
         </div>";
   }
   ?>
</div>
</body>
</html>