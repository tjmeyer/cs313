<!DOCTYPE html>
<html lang="en">
<head>
	<title>Assignment 2 - PHP Survey</title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   
   <!-- Main CSS Style sheet for general styling -->
   <link rel="stylesheet" href="../css/main.css">
   
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
   
   <script src="../js/main.js"></script>
   
</head>
<body>
   <!--Display Survey -->
   <?php
   // Displays as a simple table
   function display($data)
   {
      echo "<table>\n";
         foreach(evaluatePercents($data) as $i=>$iValue)
         {
            echo "<tr>\n";
            echo "<td style=\"color:$i;\">".strtoupper($i)."</td><td>".number_format((float)$iValue,2,'.','')."%</td>\n";
            echo "</tr>\n";
         }
      echo "</table>\n";
   }
   ?>
   <!--EndDisplay-->
   <!--Evaluate Percentage of each item (O=logn)-->
   <?php
      function evaluatePercents($fileName)
      {
         $data = array();
         $total = 0;
         $fileIn = fopen($fileName,"r") or die ("<h1 class='error'>Cannot open $fileName</h1>");
         while(!feof($fileIn))
         {
            $data[fgets($fileIn)] = 0;
            $total++;
         }
         // kill extra line that gets dragged in with fgets()
         $total--;
         unset($data[0]);
         fclose($fileIn);
         foreach($data as $i=>&$iValue)
         {
            $fileIn = fopen($fileName,"r") or die ("<h1 class='error'>Cannot open $fileName 2</h1>");
            while(!feof($fileIn))
            {
               if($i == fgets($fileIn))
               {
                  $iValue++;
               }
            }
            fclose($fileIn);
            
            // transform into a percent
            $iValue /= $total / 100.0;
         }
         return $data;
      }
   ?>
   <!--End Evalutate Ages-->
	<div class="container">
      <!--Display input data, if available-->
      <?php
         if($_GET)
         {
            if ($name = $_GET["name"])
            {
               echo "<div class='row content-container'>\n";
               echo "<div class='col-sm-4'>\n";
                  echo "<h2>Name:</h2>\n";
               echo "</div>";
               echo "<div class='col-sm-8'>\n";
                  echo "<h2>$name</h2>\n";
               echo "</div>";
               echo "</div><hr/>";
            }
            if ($age = $_GET["age"])
            {
               echo "<div class='row content-container'>\n";
               echo "<div class='col-sm-4'>\n";
                  echo "<h2>Age:</h2>\n";
               echo "</div>";
               echo "<div class='col-sm-8'>\n";
                  echo "<h2>$age</h2>\n";
               echo "</div>";
               echo "</div><hr/>";
            }
            if ($trans = $_GET["transport"])
            {
               echo "<div class='row content-container'>\n";
               echo "<div class='col-sm-4'>\n";
                  echo "<h2>Transportation:</h2>\n";
               echo "</div>";
               echo "<div class='col-sm-8'>\n";
                  echo "<h2>".strtoupper($trans)."</h2>\n";
               echo "</div>";
               echo "</div><hr/>";
            }
            if ($color = $_GET["color"])
            {
               echo "<div class='row content-container'>\n";
               echo "<div class='col-sm-4'>\n";
                  echo "<h2>Color:</h2>\n";
               echo "</div>";
               echo "<div class='col-sm-8'>\n";
                  echo "<h2 style='color:$color;'>".strtoupper($color)."</h2>\n";
               echo "</div>";
               echo "</div><hr/>";
            }
         }
      ?>
      <!--END display-->
      <div class="row">
         <!--NAME LIST START-->
         <div class="col-sm-3 content-container">
            <?php
               if ($fileIn = fopen("name","r"))
               {
                  echo "<h2>Submitters</h2>\n";
                  while(!feof($fileIn))
                  {
                     echo fgets($fileIn);
                     echo "<br/>";
                  }
                  fclose($fileIn);
               }
               else
               {
                  echo "<h2 class='error'>No name list Found!</h2>\n";
               }
            ?>
         </div>
         <!--Name list end-->
         <div class="col-sm-8 content-container">
            <h1>Age Survey</h1><hr/>
            <?php
               display("age");
            ?>
         </div>
         <div class="col-sm-8 content-container">
            <h1>Color Survey</h1><hr/>
            <?php
               display("color");
            ?>
         </div>
         <div class="col-sm-8 content-container">
            <h1>Transportation Survey</h1><hr/>
            <?php
               display("mode");
            ?>
         </div>
      </div>
      <!-- Test IO END-->
	</div>
</html>
