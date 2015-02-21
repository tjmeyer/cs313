<?php
/******************
* This returns the new account's ID
******************/ 
function createAccount($db)
{
   try
   {
      // the account table only includes an AUTO_INCREMENT id, 
      // it's entirely for reference
      $query = "INSERT INTO account VALUES ()";
      $statement = $db->prepare($query);
      $statement->execute();
      return $db->lastInsertId();
   }
   catch (Exception $e)
   {
      die("CREATE ACCOUNT ERROR: ".$e);
   }
}
 
/*****************************
* Optionally creates a master user with a new account.
*
* REQUIRES THE FOLLOWING IN $VALUES:
*     first_name
*     last_name
*     username
*     password
*     account_id (optional) <- ONLY IF ITS NOT A MASTER ACCOUNT BEING CREATED
******************************/
function createUser($db, $values, $master = 0)
{
   try
   {
      $query = "INSERT INTO user(first_name, last_name, username, password, master_user, account_id) VALUES (:first_name, :last_name, :username, :password, :master_user, :account_id)";
      $statement = $db->prepare($query);
      $statement->bindParam(':first_name',   $values['first_name']);
      $statement->bindParam(':last_name',    $values['last_name']);
      $statement->bindParam(':username',     $values['username']);
      $statement->bindParam(':password',     $values['password']);
      $statement->bindParam(':master_user',  $master);
      if($master)
      {
         // create a new account
         $account_id = createAccount($db);
         $statement->bindParam(':account_id',   $account_id);
      }
      else
      {
         // tag along with input account 
         $statement->bindParam(':account_id',   $values['account_id']);
      }
      $statement->execute();
   }
   catch (Exception $e)
   {
      die("CREATE USER ERROR: ".$e);
   }
}

/*********************
* REQUIRES THE FOLLOWING IN $VALUES:
*     name
*     account_id
*     user_id
***********************/
function createPhone($db, $values)
{
   try
   {
      $query = "INSERT INTO phone(name, account_id, user_id, connection) VALUES (:name, :account_id, :user_id, 1)";
      $statement = $db->prepare($query);
      $statement->bindParam(':name', $values['name']);
      $statement->bindParam(':account_id', $values['account_id']);
      $statement->bindParam(':user_id', $values['user_id']);
      $statement->execute();
   }
   catch(Exception $e)
   {
      die("CREATE PHONE ERROR: ".$e);
   }
}

/*********************
* REQUIRES THE FOLLOWING IN $VALUES:
*     lat
*     lon
*     alt
*     phone_id
***********************/
function createHistory($db, $values)
{
   try
   {
      $query = "INSERT INTO location(latitude, longitude, altitude, phone_id, time_stamp) VALUES (:lat, :lon, :alt, :phone_id, :time)";
      $date = date('Y-m-d G:i:s');
      $statement = $db->prepare($query);
      $statement->bindParam(':lat',       $values['lat']);
      $statement->bindParam(':lon',       $values['lon']);
      $statement->bindParam(':alt',       $values['alt']);
      $statement->bindParam(':phone_id',  $values['phone_id']);
      $statement->bindParam(':time',      $date);
      $statement->execute();
   }
   catch(Exception $e)
   {
      die("CREATE HISTORY ERROR: ".$e);
   }
}

function checkUniqueUsername($db, $username)
{
   $statement = $db->prepare("SELECT username FROM user WHERE username = '".$username."'");
   $statement->execute();
   if ($row = $statement->fetch(PDO::FETCH_ASSOC))
   {
      return TRUE;
   }
   return FALSE;
}
?>