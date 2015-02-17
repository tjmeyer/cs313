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
   
   $phone_name = "";
   $phone_name_error = "";
   $goodEntry = "<span style='color:green;' class='glyphicon glyphicon-ok'></span>";

   if ($_SERVER["REQUEST_METHOD"] == "POST")
   {
      if (empty($_POST['phone_name']))
      {
         $phone_name_error = "Phone name is required";
      }
      else
      {
         $phone_name = $_POST['phone_name'];
         if (!preg_match("/^[a-zA-Z0-9 ]*$/", $phone_name))
         {
            $phone_name_error = "Can only contain letters and numbers.";
         }
         else
         {
            $phone_name_error = $goodEntry ;
         }
      }
      if ($phone_name_error === $goodEntry)
      {
         try
         {
            $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            // get account_id
            foreach($db->query("SELECT account_id FROM user WHERE username = '".$username."'") as $row)
            {
               $account = $row[0];
            }
            
            $query = "INSERT INTO phone(name, dateCreated, connection, account_id) VALUES(:name, :date, :connection, :account_id)";
            $statement = $db->prepare($query);
            $connection = 1;
            $timestamp = time();
            $statement->bindParam(':name', $phone_name);
            $statement->bindParam(':date', $timestamp);
            $statement->bindParam(':connection', $connection);
            $statement->bindParam(':account_id', $account);
            $statement->execute();
            
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
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CellSpan - Add Phone</title>
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
            <a class="btn btn-info btn-md" href="./accountsummary.php"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
            <a class="btn btn-info btn-md" href="./logout.php">Log Out <span class="glyphicon glyphicon-log-out"></span></a>
         </div>
      </div>
      <div class="col-sm-12">
         <h1 style="text-align:center;">New Phone</h1>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-12 content-container">
         <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="create_phone">
            <div class="row">
               <br/>
               <div class="col-sm-2">
                  <label><h4>Phone Name</h4></label>
               </div>
               <div class="col-sm-6">
                  <input type="text" class="form-control input-lg" name="phone_name" maxlength="50" placeholder="Phone Name" value="<?php echo $phone_name;?>"/>
               </div>
               <div class="col-sm-4">
                  <span id="phone_name_error" style="color:red;"><?php echo $phone_name_error; ?></span>
               </div>
            </div>
            <br/>
            <div class="row" style="text-align:center;">
               <input type="submit" class="btn btn-primary btn-lg"/>
            </div>
            <br/>
         </form>
      </div>
   </div> 
     
</div>
</body>
</html>