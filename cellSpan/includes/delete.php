<?php 
function deleteHistory($db, $history_id)
{
   foreach ($history_id as $row_id)
   {
      try
      {
         $query = ("DELETE FROM history WHERE id = :id");
         $statement = $db->prepare($query);
         $statement->bindParam(':id', $row_id);
         $statement->execute();
      }
      catch(Exception $e)
      {
         die("DELETE HISTORY ERROR: ".$e);
      }
   }
}

function deletePhone($db, $phone_ids)
{
   // Get phone's location id's for each input phone
   foreach($phone_ids as $phone_id)
   {
      try
      {
         $statement = $db->query("SELECT * FROM history WHERE phone_id = $phone_id");
         $phone_locations = $statement->fetchAll(PDO::FETCH_ASSOC);
         deleteHistory($db, $phone_locations);
         
         //Now delete phone 
         $query = ("DELETE FROM phone WHERE id = :id");
         $statement = $db->prepare($query);
         $statement->bindParam(':id', $phone_id);
         $statement->execute();
      }
      catch (Exception $e)
      {
         die("DELETE PHONE ERROR: ".$e);
      }
   }
}

function deleteUser($db, $user_ids)
{
   foreach($user_ids as $user_id)
   {
      try
      {
         // delete user's phone(s)
         $statement = $db->query("SELECT * FROM phone WHERE user_id = $user_id");
         $phones = $statement->fetchAll(PDO::FETCH_ASSOC);
         deletePhone($db, $phones);
         
         //Now delete user 
         $query = ("DELETE FROM user WHERE id = :id");
         $statement = $db->prepare($query);
         $statement->bindParam(':id', $user_id);
         $statement->execute();
      }
      catch(Exception $e)
      {
         die("DELETE USER ERROR: ".$e);
      }
   }
}

function deleteAccount($db, $account_ids)
{
   foreach($account_ids as $account_id)
   {
      try
      {
         // delete account's user(s)
         $statement = $db->query("SELECT * FROM user WHERE account_id = $account_id");
         $users = $statement->fetchAll(PDO::FETCH_ASSOC);
         deleteUser($db, $users);
         
         //Now delete account(s) 
         $query = ("DELETE FROM account WHERE id = :id");
         $statement = $db->prepare($query);
         $statement->bindParam(':id', $account_id);
         $statement->execute();
      }
      catch(Exception $e)
      {
         die("DELETE ACCOUNT ERROR: ".$e);
      }
   }
}
?>