<?php
   require("includes/authenticate.php");
   require("includes/dbConnector.php");
   require("includes/password.php");
   require("includes/create.php");
   require("includes/errorChecker.php");
   
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
   
   // Only master users can add users
   if ($user['master_user'] != 1)
   {
      header('Location: ./accountSummary.php');
      die('NOT A MASTER USER ACCOUNT');
   }
   
   $headerMessage = "<h1 style='text-align:center;'>Add User to Account</h1>";
   // Initialize all basic values
   $errorArray = array(
      'first_name'   => "",
      'last_name'    => "",
      'email'        => "",
      'username'     => "",
      'password'     => "",
      'password_confirm'   => ""
   );
   
   $entryArray = array(
      'first_name'   => "",
      'last_name'    => "",
      'email'        => "",
      'username'     => "",
      'password'     => "",
      'password_confirm'   => "",
      'account_id'   => $user['account_id']
   );
   $goodEntry = "<h4 style='text-align:center;color:green;'><span class='glyphicon glyphicon-ok'></span></h4>";
      
   // Error checking
   if ($_SERVER["REQUEST_METHOD"] == "POST")
   {
      $valid = FALSE;
      foreach($errorArray as $key => $value)
      {
         $value = errorCheck($_POST[$key], $key, $goodEntry);
         if ($value === $goodEntry)
         {
            $entryArray[$key] = $_POST[$key];
         }
         $errorArray[$key] = $value;
      }
      
      if($entryArray['username'] == $goodEntry)
      {
         if(checkUniqueUsername($db, $_POST['username']))
         {
            $errorArray['username'] = "This username has already been taken.";
         }
         else
         {
            $errorArray['username'] = $goodEntry;
            $entryArray['username'] = $_POST['username'];
         }
      }
      
      // compare password field
      if ($_POST['password_confirm'] !== $_POST['password'])
      {
         $errorArray['password_confirm'] = "Password must match";
      }
      else
      {
         $errorArray['password_confirm'] = $goodEntry;
      }
      
      if ($errorArray['first_name'] == $goodEntry &&
            $errorArray['last_name'] == $goodEntry &&
            $errorArray['email'] == $goodEntry &&
            $errorArray['username'] == $goodEntry &&
            $errorArray['password'] == $goodEntry &&
            $errorArray['password_confirm'] == $goodEntry)
      {
         // reassigning $_POST array with a hashed version of the password, since it's valid.
         $entryArray['password'] = password_hash($entryArray['password'], PASSWORD_DEFAULT);
         createUser($db, $entryArray);
         $headerMessage = "<h1 style='text-align:center;color:green'>User Saved Successfully</h1>";
         
      }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CellSpan - New User</title>
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
            <a href='./accountSettings.php' type='button' class='btn btn-primary'><span class='glyphicon glyphicon-arrow-left'></span> Back to Account Settings</a>
         </div>
      </div>
      <div class="col-sm-12 content-container">
         <?php echo $headerMessage;?>
      </div>
   </div><!--END HEADER-->
   <div class="row">
         <div class="col-sm-12 content-container">
         <br/>
         <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="create_account">
            <div class="row">
               <div class="col-sm-2">
                  <label>First Name</label>
               </div>
               <div class="col-sm-6">
                  <input type="text" class="form-control input-lg" name="first_name" maxlength="50" placeholder="First Name" value="<?php echo $entryArray['first_name'];?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="first_name_error" style="color:red;"><?php echo $errorArray['first_name']; ?></span>
               </div>
            </div>
            <br/>
            <div class="row">
               <div class="col-sm-2">
                  <label>Last Name</label>
               </div>
               <div class="col-sm-6">
                  <input type="text" class="form-control input-lg" name="last_name" maxlength="50" placeholder="Last Name" value="<?php echo $entryArray['last_name'];?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="last_name_error" style="color:red;"><?php echo $errorArray['last_name'];?></span>
               </div>
            </div>
            <br/>
            <div class="row">
               <div class="col-sm-2">
                  <label>Email</label>
               </div>
               <div class="col-sm-6">
                  <input type="text" class="form-control input-lg" name="email" maxlength="100" placeholder="Email" value="<?php echo $entryArray['email'];?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="email_error" style="color:red;"><?php echo $errorArray['email'];?></span>
               </div>
            </div>
            <br/>
            <div class="row">
               <div class="col-sm-2">
                  <label>Username</label>
               </div>
               <div class="col-sm-6">
                  <input type="text" class="form-control input-lg" name="username" maxlength="50" placeholder="Username" value="<?php echo $entryArray['username'];?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="username_error" style="color:red;"><?php echo $errorArray['username'];?></span>
               </div>
            </div>
            <br/>
            <div class="row">
               <div class="col-sm-2">
                  <label>Password</label>
               </div>
               <div class="col-sm-6">
                  <input type="password" class="form-control input-lg" name="password" maxlength="50" placeholder="Password" value=""/>
               </div>
               <div class="col-sm-4">
                  <span id="password_error" style="color:red;"><?php echo $errorArray['password'];?></span>
               </div>
            </div>
            <br/>
            <div class="row">
               <div class="col-sm-2">
                  <label>Confirm Password</label>
               </div>
               <div class="col-sm-6">
                  <input type="password" class="form-control input-lg" name="password_confirm" maxlength="50" placeholder="" value="<?php echo $entryArray['password_confirm'];?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="password_confirm_error" style="color:red;"><?php echo $errorArray['password_confirm'];?></span>
               </div>
            </div>
            <br/>
            <div class="row">
               <div style="text-align:center;">
                  <input type="submit" class="btn btn-primary btn-lg" value="Submit"/>
               </div>
            </div>
            <br/>
         </form>
      </div>
   </div>   
</div>
</body>
</html>