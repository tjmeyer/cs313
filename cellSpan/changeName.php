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
   
   $errorArray = array(
      "first_name"   => "",
      "last_name"    => ""
   );
   
   $entryArray = array(
      "first_name"   =>  "",
      "last_name"    =>  ""
   );
   $goodEntry = "<span style='color:green;' class='glyphicon glyphicon-ok'></span>";

   if ($_SERVER["REQUEST_METHOD"] == "POST")
   {
      foreach($errorArray as $key => $value)
      {
         $value = errorCheck($_POST[$key], $key, $goodEntry);
         if ($value === $goodEntry)
         {
            $entryArray[$key] = $_POST[$key];
         }
         $errorArray[$key] = $value;
      }
      
      if ($errorArray["first_name"] == $goodEntry &&
            $errorArray["last_name"] == $goodEntry)
      {
         $query = "UPDATE user SET first_name = :first_name, last_name = :last_name WHERE id = ".$user['id'];
         $statement = $db->prepare($query);
         $statement->bindParam(':first_name',   $entryArray['first_name']);
         $statement->bindParam(':last_name',    $entryArray['last_name']);
         $statement->execute();
         header("Location: ./accountSettings.php");
         die("User Name Saved");
      }
   }
   
?>