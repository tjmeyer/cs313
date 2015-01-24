<?php
   $cookieName = "surveyAccess";
   $cookieValue = 0;
   if(isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] == 1)
   {
      $forwardLocation = "processSurvey.php";
      header("Location:$forwardLocation",1);
   }
   else
   {
      setcookie($cookieName,$cookieValue,time()+10*365*24*60*60);
   }
?>
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
   <!--Form Submission -->
   <?php
   function formToFile($data)
   {
      foreach($data as $i => $i_value)
      {
         $i_value = "$i_value\n";
         $fileOut = fopen($i,"a") or die("<h1>Could not open $i list.</h1>");
         fwrite($fileOut,$i_value);
         fclose($fileOut);
      }
   }
   ?>
   <!--END Form Submission-->

   <!--Form check -->
   <?php
      $nameErr = $ageErr = "";
      $name = $age = "";
      
      if($_SERVER["REQUEST_METHOD"] == "POST")
      {
         if(empty($_POST["name"]))
         {
            $nameErr = "Name is required";
         }
         else if (!preg_match("/^[a-zA-Z]*$/",$_POST["name"]))
         {
            $nameErr = "Only Letters, no spaces, bohoo";
         }
         else
         {
            $name = test_input($_POST["name"]);
         }
         if(empty($_POST["age"]))
         {
            $ageErr = "Age is required";
         }
         else if (!preg_match("/^[0-9]*$/",$_POST["age"]))
         {
            $ageErr = "Only digits please!";
         }
         else
         {
            $age = test_input($_POST["age"]);
         }
         
         if($nameErr == "" && $ageErr == "")
         {
            $mode = $_POST['transport'];
            $color = $_POST["color"];
            $data = array("name"=>$name,"age"=>$age,"color"=>$_POST["color"],"mode"=>$_POST["transport"]);
            formToFile($data);
            $destinationURL = "processSurvey.php?name=$name&age=$age&transport=$mode&color=$color";
            setcookie($cookieName, 1, time()+10*365*24*60*60);
            
            header("Location:$destinationURL");
            exit();
         }
      }
      
      function test_input($data)
      {
         $data = trim($data);
         $data = stripslashes($data);
         $data = htmlspecialchars($data);
         return $data;
      }
   ?>
   <!--END form Check -->
   
	<div class="container">
      <!-- Test IO START-->
      <div class="row content-container">
         <div class="col-sm-12">
            <h1>Simple Survey with COOKIES, mmmmmm</h1>
         </div>
      </div>
      <!-- Test IO END-->
      <hr/>
      <!--Form START-->
      <div class="row content-container">
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <!--column 1-->
            <div class="col-sm-6">
               What should I call you?:<br/> <input type="text" name="name" value="<?php echo $name;?>"><span class="error">* <?php echo $nameErr; ?></span>
               <br/>
               How do you get to school?: <br/>
                  <input type="radio" name="transport" value="car" checked>Car<br/>
                  <input type="radio" name="transport" value="bike">Bike<br/>
                  <input type="radio" name="transport" value="walk">Yup, I walk...<br/>
            </div>
            <!--column 2-->
            <div class="col-sm-6">
               What's your favorite color in this list:
               <select name="color">
                  <option value="red">Red</option>
                  <option value="green">Green</option>
                  <option value="blue">Blue</option>
                  <option value="yellow">Yellow</option>
                  <option value="purple">Purple</option>
                  <option value="charizard">Charizard!</option>
               </select><br/>
               How old are you?: <input type="text" maxlength="2" size="2" name="age" value="<?php echo $age?>"><span class="error">* <?php echo $ageErr; ?></span>
               <br/>
               <input type="submit">
            </div>
         </form>
      </div>
      <!--Form END-->
	</div>
</html>
