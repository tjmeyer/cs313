<?php 
   require("dbConnector.php");
   try
   {
      $db = loadDatabase();
      session_start();
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

<?php
   $first_name_error = "";
   $last_name_error = "";
   $email_error = "";
   $username_error = "";
   $password_error = "";
   $password_confirm_error = "";

   $email = "";
   $first_name = "";
   $last_name = "";
   $username = "";
   $password = "";
   $password_confirm = "";
   
   $goodEntry = "<span style='color:green;' class='glyphicon glyphicon-ok'></span>";
      
   function checkDBforUser($input)
   {
      $db = loadDatabase();
      $statement = $db->prepare("SELECT username FROM user WHERE username = '".$input."'");
      $statement->execute();
      if ($row = $statement->fetch(PDO::FETCH_ASSOC))
      {
         return "<span style='color:red;' class='glyphicon glyphicon-remove'>$input already exists</span>";
      }
      return "<span style='color:green;' class='glyphicon glyphicon-ok'></span>";
   }
   
   // Error checking
   if ($_SERVER["REQUEST_METHOD"] == "POST")
   {
      if (empty($_POST['email']))
      {
         $email_error = "Email is required";
      }
      else
      {
         $email = $_POST['email'];
         if (!filter_var($email, FILTER_VALIDATE_EMAIL))
         {
            $email_error = "Invalid email format";
         }
         else
         {
            $email_error = $goodEntry ;
         }
      }
      if (empty($_POST['first_name']))
      {
         $first_name_error = "First name is required";
      }
      else
      {
         $first_name = $_POST['first_name'];
         if (!preg_match("/^[a-zA-Z]*$/", $first_name))
         {
            $first_name_error = "Can only contain letters.";
         }
         else
         {
            $first_name_error = $goodEntry ;
         }
      }
      if (empty($_POST['last_name']))
      {
         $last_name_error = "Last name is required";
      }
      else
      {
         $last_name = $_POST['last_name'];
         if (!preg_match("/^[a-zA-Z]*$/", $last_name)){
            $last_name_error = "Can only contain letters.";
         }
         else{
            $last_name_error = $goodEntry ;
         }
      }
      if (empty($_POST['last_name']))
      {
         $last_name_error = "Last name is required";
      }
      else
      {
         $last_name = $_POST['last_name'];
         if (!preg_match("/^[a-zA-Z]*$/", $last_name)){
            $last_name_error = "Can only contain letters.";
         }
         else{
            $last_name_error = $goodEntry ;
         }
      }
      if (empty($_POST['username']))
      {
         $username_error = "Last name is required";
      }
      else
      {
         $username = $_POST['username'];
         if (preg_match("/\\s/", $username)){
            $username_error = "Cannot contain spaces";
         }
         else
         {
            $username_error = checkDBforUser($username);
         }
      }
      if (empty($_POST['password']))
      {
         $password_error = "Password is required";
      }
      else
      {
         $password = $_POST['password'];
         if (strlen(trim($password)) < 8)
         {
            $password_error = "Password must be greater than 8 charaters.";
         }
         else
         {
            $password_error = $goodEntry;
         }
      }
      if ($_POST['confirm_password'] != $_POST['password'])
      {
         $password_confirm_error = "Does not match";
      }
      else
      {
         $password_confirm = $_POST['confirm_password'];
         $password_confirm_error = $goodEntry;
      }
      
      // Submit to DB
      if ($first_name_error == $goodEntry &&
            $last_name_error == $goodEntry &&
            $email_error == $goodEntry &&
            $username_error == $goodEntry &&
            $password_error == $goodEntry &&
            $password_confirm_error == $goodEntry)
            {
               try{
                  $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                  $query = "INSERT INTO account(status) VALUES(:status)";
                  $statement = $db->prepare($query);
                  $status = 'a';
                  $statement->bindParam(':status', $status);
                  $statement->execute();
                  
                  $last_id = $db->lastInsertId();
                  
                  $query = 'INSERT INTO user(first_name, last_name, username, email, password, account_id) VALUES(:first_name, :last_name, :username, :email, :password, :account_id)';
                  $statement = $db->prepare($query);
                  
                  $statement->bindParam(':first_name', $first_name);
                  $statement->bindParam(':last_name', $last_name);
                  $statement->bindParam(':username', $username);
                  $statement->bindParam(':email', $email);
                  $statement->bindParam(':password', $password);
                  $statement->bindParam(':account_id', $last_id);
               
                  $statement->execute();
                  $_SESSION['pass'] = $password;
                  setcookie('user', $username, time() + (86400*30));
                  header("Location: ./accountsummary.php");
                  die();
               }
               catch(Exception $e)
               {
                  die("DATABASE ERROR: ".$e);
               }
               
               
            }
   }
?>


<div class="container">
   <div class="row">
      <br/><br/><br/><!--Spacer-->
   </div>
   <div class="row">
         <div class="col-sm-12 content-container">
         <h2 style="text-align:center;">Create Account</h2>
         <hr/>
         <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="create_account">
            <div class="row">
               <div class="col-sm-2">
                  <label>First Name</label>
               </div>
               <div class="col-sm-6">
                  <input type="text" class="form-control input-lg" name="first_name" maxlength="50" placeholder="First Name" value="<?php echo $first_name;?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="first_name_error" style="color:red;"><?php echo $first_name_error; ?></span>
               </div>
            </div>
            <br/>
            <div class="row">
               <div class="col-sm-2">
                  <label>Last Name</label>
               </div>
               <div class="col-sm-6">
                  <input type="text" class="form-control input-lg" name="last_name" maxlength="50" placeholder="Last Name" value="<?php echo $last_name;?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="last_name_error" style="color:red;"><?php echo $last_name_error;?></span>
               </div>
            </div>
            <br/>
            <div class="row">
               <div class="col-sm-2">
                  <label>Email</label>
               </div>
               <div class="col-sm-6">
                  <input type="text" class="form-control input-lg" name="email" maxlength="100" placeholder="Email" value="<?php echo $email;?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="email_error" style="color:red;"><?php echo $email_error;?></span>
               </div>
            </div>
            <br/>
            <div class="row">
               <div class="col-sm-2">
                  <label>Username</label>
               </div>
               <div class="col-sm-6">
                  <input type="text" class="form-control input-lg" name="username" maxlength="50" placeholder="Username" value="<?php echo $username;?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="username_error" style="color:red;"><?php echo $username_error;?></span>
               </div>
            </div>
            <br/>
            <div class="row">
               <div class="col-sm-2">
                  <label>Password</label>
               </div>
               <div class="col-sm-6">
                  <input type="password" class="form-control input-lg" name="password" maxlength="50" placeholder="Password" value="<?php echo $password;?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="password_error" style="color:red;"><?php echo $password_error;?></span>
               </div>
            </div>
            <br/>
            <div class="row">
               <div class="col-sm-2">
                  <label>Confirm Password</label>
               </div>
               <div class="col-sm-6">
                  <input type="password" class="form-control input-lg" name="confirm_password" maxlength="50" placeholder="" value="<?php echo $password_confirm;?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="password_confirm_error" style="color:red;"><?php echo $password_confirm_error;?></span>
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