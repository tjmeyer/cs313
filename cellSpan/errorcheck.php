<?php

$goodEntry = "";

function numberCheck($input)
{
   foreach($input as $checkValue)
   {
      $data = array(
         "error" => "",
         "safe"   => $goodEntry
      );
      if (empty($_POST['lat']))
      {
         $latErr = "huh?";
      }
      else
      {
         $lat = $_POST['lat'];
         if (!is_numeric($lat))
         {
            $latErr = "Can only contain numbers.";
         }
         else
         {
            $lat = $_POST['lat'];
            $latErr = $goodEntry ;
         }
      }
   }
}

?>