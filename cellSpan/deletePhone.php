<?php 
   require("includes/authenticate.php");
   require("includes/dbConnector.php");
   try
   {
      $db = loadDatabase();
      $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      session_start();
      
      // Check for valid user
     
      if (verify($_SESSION, 'user'))
      {
         // Fetch Information
         $username = $_SESSION['user'];
         $statement = $db->query("SELECT * FROM user WHERE username = '".$username."'");
         $user = $statement->fetch(PDO::FETCH_ASSOC);
      }
      else
      {
         logout();
      }
   }
   catch (PDOException $e)
   {
      echo "DATABASE ERROR: ".$e->getMessage();
      die();
   }
   
   if ($_SERVER["REQUEST_METHOD"] == "GET" && $user['master_user'] == 1)
   {
      $valid = false;
      foreach($db->query("SELECT p.id FROM user u JOIN phone p ON u.account_id = p.account_id WHERE username = '".$username."'") as $row)
      {
         if ($row['id'] === $_GET['id'])
         {
            $valid = true;
         }
      }
      if($valid)
      {
         $query = "DELETE FROM location WHERE phone_id = :id";
         $statement = $db->prepare($query);
         $statement->bindParam(':id', $_GET['id']);
         $statement->execute();
         $query = "DELETE FROM phone WHERE id = :id";
         $statement = $db->prepare($query);
         $statement->bindParam(':id', $_GET['id']);
         $statement->execute();
         header("Location: ./accountSummary.php");
         die();
      }
      else
      {
         echo "<h1 class='content-container'>You are not authorized to edit this entry</h1>";
         die();
      }
   }
   else
   {
      header("Location: ./accountSummary.php");
      die();
   }
?>
