<?php

function loadDatabase()
{
   // strictly for online phone client -> db access
   $dbHost = "localhost";
   $dbPort = "";
   $dbUser = "root";
   $dbPassword = "kumite2";
   $dbName = "nex";

   $openShiftVar = getenv('OPENSHIFT_MYSQL_DB_HOST');

     if ($openShiftVar === null || $openShiftVar == "")
     {
          // Not in the openshift environment
          // echo "Using local credentials: "; 
     }
     else 
     { 
         $dbHost = "127.3.95.130";
         $dbPort = "3306";
         $dbUser = "phoneAccess";
         $dbPassword = "arL4WV34b3EbGYZZ";
         $dbName = "java313";
     }
   // echo "host:$dbHost:$dbPort dbName:$dbName user:$dbUser password:$dbPassword<br >\n";
   $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);

   return $db;
}
?>