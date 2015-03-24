<?php

include("phoneDBConnector.php");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
   $db = loadDatabase();
   $lat = $_POST['lat'];
   $lon = $_POST['lon'];
   $time = $_POST['time'];
   $uuid = $_POST['uuid'];
   
   if($lat && $lon && $time && $uuid != "null") //if none are null or invalid
   {
      try {
         //pull phone from db
         $query = "SELECT id FROM phone WHERE mac = \"".$uuid."\"";
         $statement = $db->query($query);
         $phone = $statement->fetch(PDO::FETCH_ASSOC);
      } catch (Exception $e) {
         die("SELECT ERROR: ".$e);
      }
      
      try {
         //insert new location
         $query = "INSERT INTO location(latitude, longitude, phone_id, time_stamp) VALUES (:lat, :lon, :phone_id, :time)";
         $timestamp = $time/1000;
         $date = date("Y-m-d", $timestamp);
         $statement = $db->prepare($query);
         $statement->bindParam(":lat", $lat);
         $statement->bindParam(":lon", $lon);
         $statement->bindParam(":phone_id", $phone['id']);
         $statement->bindParam(":time", $date);
         $statement->execute();
      } catch (Exception $e) {
         die("INSERT ERROR: ".$e);
      }
      
   }
}
?>