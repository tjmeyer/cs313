<?php 
include("phoneDBConnector.php");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
   $db = loadDatabase();
   
   $username = $_POST['username'];
   $password = $_POST['password'];
   $uuid     = $_POST['uuid'];
   
   $valid = "invalid";
   
   if($username && $password) //both are not null
   {
      $id;
      $accountId;
      try {
         //pull user from db and check password
         $query = "SELECT first_name, account_id, id, password FROM user WHERE username = \"".$username."\"";
         $statement = $db->query($query);
         $user = $statement->fetch(PDO::FETCH_ASSOC);
         if($password === $user['password'])
         {
            $id = $user['id'];
            $valid = "valid";
         }
      } catch (Exception $e) {
         echo "SELECT ERROR: ".$e;
         die("SELECT ERROR: ".$e);
      }
      
      try {
         //insert new phone
         $query = "INSERT INTO phone(mac, name, connection, account_id, user_id) VALUES (:mac, :name, :conn, :account, :user)";
         $phoneName = $user['first_name']."'s Phone";
         $statement = $db->prepare($query);
         $statement->bindParam(":mac", $uuid);
         $statement->bindParam(":name", $phoneName);
         $statement->bindParam(":conn", 1);
         $statement->bindParam(":account", $user['account_id']);
         $statement->bindParam(":user", $user['id']);
         $statement->execute();
      } catch (Exception $e) {
         echo "INSERT ERROR: ".$e;
         die("INSERT ERROR: ".$e);
      }
      
      echo $valid;
   }
   
}
?>