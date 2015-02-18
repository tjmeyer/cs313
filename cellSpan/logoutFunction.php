<?php 
function logout()
{
   session_destroy();
   if (isset($_SERVER['HTTP_COOKIE'])) 
   {
      $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
      foreach($cookies as $cookie) 
      {
         $parts1 = explode('=', $cookie);
         $name = trim($parts[0]);
         setcookie($name, '', time()-1000);
         setcookie($name, '', time()-1000, '/');
      }
   }
   header("Location: ./login.php");
   die();
}
?>