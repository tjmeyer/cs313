<?php
function alphaOnly($input, $goodEntry)
{
   if (!preg_match("/^[a-zA-Z]*$/", $input))
   {
      return "Can only contain letters.";
   }
   else
   {
      return $goodEntry;
   }
}

function errorCheck($value, $key, $goodEntry = 'Good Entry')
{
   
   $evaluation = "";
   
   // Check if empty
   if($value == NULL || $value == "")
   {
      $readable = str_replace('_', ' ', $key);
      $evaluation = ucfirst($readable)." cannot be empty.";
   }
   else
   {
      if(strpos($key, 'name'))
      {
         $readable = str_replace('_', ' ', $key);
         $evaluation = alphaOnly($value, $goodEntry);
      }
      else if ($key == 'email')
      {
         if (!filter_var($value, FILTER_VALIDATE_EMAIL))
         {
            $evaluation = "Invalid email format.";
         }
         else
         {
            $evaluation = $goodEntry;
         }
      }
      else if ($key == 'username')
      {
         if (preg_match("/\\s/", $value))
         {
            $evaluation = "Cannot contain spaces.";
         }
         else
         {
            $evaluation = $goodEntry;
         }
      }
      else if ($key == 'password')
      {
         if (strlen(trim($value)) < 8)
         {
            $evaluation = "Password must be greater than 8 characters.";
         }
         else
         {
            $evaluation = $goodEntry;
         }
      }
      else
      {
         $evaluation = "ERROR: errorChecker could not evaluate this entry. NOT LISTED AS VALID ENTRY!";
      }
   }

   return $evaluation;
}

?>