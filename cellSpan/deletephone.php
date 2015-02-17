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
   
   if ($_SERVER["REQUEST_METHOD"] == "GET")
   {
      $query = "SELECT p.id FROM user u JOIN phone p ON u.account_id = p.account_id WHERE username = '".$username."'";
   }
   else
   {
      header("Location: ./accountsummary.php");
      die();
   }
?>