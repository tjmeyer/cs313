<?php 
   require("dbConnector.php");
   try
   {
      $db = loadDatabase();
      
      $message = "<h2 style='text-align:center;'>Welcome Back!</h2>";
      $username = "";
      $password = "";
      if ($_SERVER["REQUEST_METHOD"] == "POST")
      {
         $username = $_POST['username'];
         $password = $_POST['password'];
         
         $statement = $db->query("SELECT username, password FROM user WHERE username = '".$username."'");
         
         if ($row = $statement->fetch(PDO::FETCH_ASSOC))
         {
            if ($row['password'] === $password)
            {
               session_start();
               $_SESSION['pass'] = $password;
               setcookie('user', $username, time() + (86400*30));
               header("Location: ./accountsummary.php");
               die();
            }
            else
            {
               $message = "<h2 style='color:red;text-align:center;'>Incorrect Login</h2>";
            }
         }
      }
   }
   catch (PDOException $e)
   {
      echo "ERROR: ".$e->getMessage();
      die();
   }

   ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CellSpan Login</title>
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
   <div class="row">
      <br/><br/><br/><!--Spacer-->
   </div>
   <div class="row">
      <div class="col-sm-4"></div>
      <div class="col-sm-4 content-container">
         <?php echo $message;?>
         <hr/>
         <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="loginForm">
            <p><input type="text" class="form-control input-lg" name="username" placeholder="username"/></p>
            <p><input type="password" class="form-control input-lg" name="password" placeholder="password"/></p>
            <p><div style="text-align:center;"><input type="submit" value="login" class="btn btn-primary btn-lg"/>
            <a href="./createaccount.php" class="btn btn-default btn-lg">New Account <span class="glyphicon glyphicon-plus"></span></a></div></p>
         </form>
      </div>
      <div class="col-sm-4"></div>
   </div>   
</div>
</body>
</html>