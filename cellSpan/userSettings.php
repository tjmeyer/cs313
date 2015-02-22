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
   
   // get session user info
   $statement = $db->query("SELECT * FROM user WHERE username = '".$_SESSION['user']."'");
   $user = $statement->fetch(PDO::FETCH_ASSOC);
   
   // get user info for the user's settings in question
   if (isset($_GET['username']))
   {
      $statement = $db->query("SELECT * FROM user WHERE username = '".$_GET['username']."'");
      $userSetting = $statement->fetch(PDO::FETCH_ASSOC);
   }
   else
   {
      die('INVALID GET STATEMENT');
   }
   
   // verify user has master privileges and is on the same account as user in question
   if($user['master_user'] != 1 && $user['account_id'] == $userSetting['account_id'])
   {
      die('NOT MASTER USER, SESSION EXPIRED');
   }
   $headerMessage = "<h1 style='text-align:center;'>".$userSetting['first_name']."'s Settings</h1>";
   // validate inputs
   $errorArray = array(
      'first_name'   => "",
      'last_name'    => ""
   );
   $goodEntry = "<span class='glyphicon glyphicon-ok' style='color:green;'></span>";
   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
      $entryArray = array(
         'first_name' => $_POST['first_name'],
         'last_name'  => $_POST['last_name'],
      );
      foreach($entryArray as $key => $value)
      {
         $errorArray[$key] = errorCheck($value, $key, $goodEntry);
         if($errorArray[$key] == $goodEntry)
         {
            $userSetting[$key] = $_POST[$key];
         }
      }
      
      $userSetting['master_user'] = $_POST['master_user'];
      if ($errorArray['first_name'] == $goodEntry &&
         $errorArray['last_name'] == $goodEntry)
      {
         if($_POST['master_user'] == 'master_user')
         {
            $master = 1;
         }
         else
         {
            $master = 0;
         }
         $query = "UPDATE user SET first_name = :first_name, last_name = :last_name, master_user = :master WHERE id = ".$userSetting['id'];
         $statement = $db->prepare($query);
         $statement->bindParam(':first_name', $_POST['first_name']);
         $statement->bindParam(':last_name', $_POST['last_name']);
         $statement->bindParam(':master', $master);
         $statement->execute();
         $headerMessage = "<h1 style='text-align:center;color:green;'>User Saved</h1>";
      }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CellSpan - User Settings</title>
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
         <?php echo $headerMessage; ?>
      </div>
   </div><!--END HEADER-->
   <div class='row content-container'>
      <form action='<?php echo htmlspecialchars('./userSettings.php?username='.$userSetting['username']);?>' method='POST'>
         <br/>
         <div class='row'><!--Start input field-->
            <div class='col-sm-1'></div> <!--buffer col-->
            <div class='col-sm-7'> <!--input col-->
               <div class="input-group input-group-lg">
                  <span class="input-group-addon" id="basic-addon1">First Name</span>
                  <input type="text" class="form-control" name="first_name" placeholder='First Name' value='<?php echo $userSetting['first_name'];?>' aria-describedby="basic-addon1">
               </div>
            </div>
            <div class='col-sm-4'> <!--error col-->
               <h4 style='color:red;'><?php echo $errorArray['first_name'];?></h4>
            </div>
         </div><!--End input field-->
         <br/>
         <div class='row'><!--Start input field-->
            <div class='col-sm-1'></div> <!--buffer col-->
            <div class='col-sm-7'> <!--input col-->
               <div class="input-group input-group-lg">
                  <span class="input-group-addon" id="basic-addon1">Last Name</span>
                  <input type="text" class="form-control" name="last_name" placeholder='Last Name' value='<?php echo $userSetting['last_name'];?>' aria-describedby="basic-addon1">
               </div>
            </div>
            <div class='col-sm-4'> <!--error col-->
               <h4 style='color:red;'><?php echo $errorArray['last_name'];?></h4>
            </div>
         </div><!--End input field-->
         <br/>
         <div class='row'><!--Start input field-->
            <div class='col-sm-1'></div> <!--buffer col-->
            <div class='col-sm-7'> <!--input col-->
               <div class="input-group input-group-lg">
                  <span class="input-group-addon" id="basic-addon1">Master Account Enabled</span>
                  <input type="checkbox" class="form-control" name="master_user" value='master_user' <?php if($userSetting['master_user'] == 1){echo 'checked';}?>>
               </div>
            </div>
            <div class='col-sm-4'> <!--error col-->
            </div>
         </div><!--End input field-->
         <hr class='divider'/>
         <div class='row' style='text-align:center;'>
            <input type='submit' value='Save' class='btn btn-primary btn-lg'/>
         </div>
         <br/>
      </form>
   </div>
</div>
</body>
</html>