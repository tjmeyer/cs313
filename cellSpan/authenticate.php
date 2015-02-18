<?php 
require("logoutFunction.php");
require("password.php");
function authenticate($username, $password, $db)
{
   try
   {
      // $db = loadDatabase();
      $statement = $db->query("SELECT username, password FROM user WHERE username = '".$username."'");

      // If something is returned from the DB
      if ($row = $statement->fetch(PDO::FETCH_ASSOC))
      {
         if (password_verify($password, $row['password']))
         {
            return TRUE;
         }
         else
         {
            // Return a failure
            return FALSE;
         }
      }
      else
      {
         return FALSE;
      }
   }
   catch (PDOException $e)
   {
      die("ERROR: ".$e->getMessage());
   }
}

function verify($session, $tag)
{
   if (isset($session[$tag]))
   {
      return TRUE;
   }
   else
   {
      return FALSE;
   }
}
?>